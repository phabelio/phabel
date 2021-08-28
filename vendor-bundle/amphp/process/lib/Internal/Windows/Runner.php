<?php

namespace Phabel\Amp\Process\Internal\Windows;

use Phabel\Amp\Deferred;
use Phabel\Amp\Loop;
use Phabel\Amp\Process\Internal\ProcessHandle;
use Phabel\Amp\Process\Internal\ProcessRunner;
use Phabel\Amp\Process\Internal\ProcessStatus;
use Phabel\Amp\Process\ProcessException;
use Phabel\Amp\Process\ProcessInputStream;
use Phabel\Amp\Process\ProcessOutputStream;
use Phabel\Amp\Promise;
use const Phabel\Amp\Process\BIN_DIR;
/**
 * @internal
 * @codeCoverageIgnore Windows only.
 */
final class Runner implements ProcessRunner
{
    const FD_SPEC = [
        ["pipe", "r"],
        // stdin
        ["pipe", "w"],
        // stdout
        ["pipe", "w"],
        // stderr
        ["pipe", "w"],
    ];
    const WRAPPER_EXE_PATH = \PHP_INT_SIZE === 8 ? BIN_DIR . '\\windows\\ProcessWrapper64.exe' : BIN_DIR . '\\windows\\ProcessWrapper.exe';
    private static $pharWrapperPath;
    private $socketConnector;
    private function makeCommand($workingDirectory)
    {
        if (!\is_string($workingDirectory)) {
            if (!(\is_string($workingDirectory) || \is_object($workingDirectory) && \method_exists($workingDirectory, '__toString') || (\is_bool($workingDirectory) || \is_numeric($workingDirectory)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($workingDirectory) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($workingDirectory) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $workingDirectory = (string) $workingDirectory;
            }
        }
        $wrapperPath = self::WRAPPER_EXE_PATH;
        // We can't execute the exe from within the PHAR, so copy it out...
        if (\strncmp($wrapperPath, "phar://", 7) === 0) {
            if (self::$pharWrapperPath === null) {
                self::$pharWrapperPath = \sys_get_temp_dir() . "amphp-process-wrapper-" . \hash('sha1', \file_get_contents(self::WRAPPER_EXE_PATH));
                \copy(self::WRAPPER_EXE_PATH, self::$pharWrapperPath);
                \register_shutdown_function(static function () {
                    @\unlink(self::$pharWrapperPath);
                });
            }
            $wrapperPath = self::$pharWrapperPath;
        }
        $result = \sprintf('%s --address=%s --port=%d --token-size=%d', \escapeshellarg($wrapperPath), $this->socketConnector->address, $this->socketConnector->port, SocketConnector::SECURITY_TOKEN_SIZE);
        if ($workingDirectory !== '') {
            $result .= ' ' . \escapeshellarg('--cwd=' . \rtrim($workingDirectory, '\\'));
        }
        $phabelReturn = $result;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function __construct()
    {
        $this->socketConnector = new SocketConnector();
    }
    /** @inheritdoc */
    public function start($command, $cwd = null, array $env = [], array $options = [])
    {
        if (!\is_string($command)) {
            if (!(\is_string($command) || \is_object($command) && \method_exists($command, '__toString') || (\is_bool($command) || \is_numeric($command)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($command) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($command) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $command = (string) $command;
            }
        }
        if (!\is_null($cwd)) {
            if (!\is_string($cwd)) {
                if (!(\is_string($cwd) || \is_object($cwd) && \method_exists($cwd, '__toString') || (\is_bool($cwd) || \is_numeric($cwd)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($cwd) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($cwd) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $cwd = (string) $cwd;
                }
            }
        }
        if (\strpos($command, "\x00") !== \false) {
            throw new ProcessException("Can't execute commands that contain null bytes.");
        }
        $options['bypass_shell'] = \true;
        $handle = new Handle();
        $handle->proc = @\proc_open($this->makeCommand(isset($cwd) ? $cwd : ''), self::FD_SPEC, $pipes, $cwd ?: null, $env ?: null, $options);
        if (!\is_resource($handle->proc)) {
            $message = "Could not start process";
            if ($error = \error_get_last()) {
                $message .= \sprintf(" Errno: %d; %s", $error["type"], $error["message"]);
            }
            throw new ProcessException($message);
        }
        $status = \proc_get_status($handle->proc);
        if (!$status) {
            \proc_close($handle->proc);
            throw new ProcessException("Could not get process status");
        }
        $securityTokens = \random_bytes(SocketConnector::SECURITY_TOKEN_SIZE * 6);
        $written = \fwrite($pipes[0], $securityTokens . "\x00" . $command . "\x00");
        \fclose($pipes[0]);
        \fclose($pipes[1]);
        if ($written !== SocketConnector::SECURITY_TOKEN_SIZE * 6 + \strlen($command) + 2) {
            \fclose($pipes[2]);
            \proc_close($handle->proc);
            throw new ProcessException("Could not send security tokens / command to process wrapper");
        }
        $handle->securityTokens = \str_split($securityTokens, SocketConnector::SECURITY_TOKEN_SIZE);
        $handle->wrapperPid = $status['pid'];
        $handle->wrapperStderrPipe = $pipes[2];
        $stdinDeferred = new Deferred();
        $handle->stdioDeferreds[] = $stdinDeferred;
        $handle->stdin = new ProcessOutputStream($stdinDeferred->promise());
        $stdoutDeferred = new Deferred();
        $handle->stdioDeferreds[] = $stdoutDeferred;
        $handle->stdout = new ProcessInputStream($stdoutDeferred->promise());
        $stderrDeferred = new Deferred();
        $handle->stdioDeferreds[] = $stderrDeferred;
        $handle->stderr = new ProcessInputStream($stderrDeferred->promise());
        $this->socketConnector->registerPendingProcess($handle);
        $phabelReturn = $handle;
        if (!$phabelReturn instanceof ProcessHandle) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessHandle, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function join(ProcessHandle $handle)
    {
        /** @var Handle $handle */
        $handle->exitCodeRequested = \true;
        if ($handle->exitCodeWatcher !== null) {
            Loop::reference($handle->exitCodeWatcher);
        }
        $phabelReturn = $handle->joinDeferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function kill(ProcessHandle $handle)
    {
        /** @var Handle $handle */
        \exec('taskkill /F /T /PID ' . $handle->wrapperPid . ' 2>&1', $output, $exitCode);
        if ($exitCode) {
            throw new ProcessException("Terminating process failed");
        }
        $failStart = \false;
        if ($handle->childPidWatcher !== null) {
            Loop::cancel($handle->childPidWatcher);
            $handle->childPidWatcher = null;
            $handle->pidDeferred->fail(new ProcessException("The process was killed"));
            $failStart = \true;
        }
        if ($handle->exitCodeWatcher !== null) {
            Loop::cancel($handle->exitCodeWatcher);
            $handle->exitCodeWatcher = null;
            $handle->joinDeferred->fail(new ProcessException("The process was killed"));
        }
        $handle->status = ProcessStatus::ENDED;
        if ($failStart || $handle->stdioDeferreds) {
            $this->socketConnector->failHandleStart($handle, "The process was killed");
        }
        $this->free($handle);
    }
    /** @inheritdoc */
    public function signal(ProcessHandle $handle, $signo)
    {
        if (!\is_int($signo)) {
            if (!(\is_bool($signo) || \is_numeric($signo))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($signo) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($signo) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $signo = (int) $signo;
            }
        }
        throw new ProcessException('Signals are not supported on Windows');
    }
    /** @inheritdoc */
    public function destroy(ProcessHandle $handle)
    {
        /** @var Handle $handle */
        if ($handle->status < ProcessStatus::ENDED && \is_resource($handle->proc)) {
            try {
                $this->kill($handle);
                return;
            } catch (ProcessException $e) {
                // ignore
            }
        }
        $this->free($handle);
    }
    private function free(Handle $handle)
    {
        if ($handle->childPidWatcher !== null) {
            Loop::cancel($handle->childPidWatcher);
            $handle->childPidWatcher = null;
        }
        if ($handle->exitCodeWatcher !== null) {
            Loop::cancel($handle->exitCodeWatcher);
            $handle->exitCodeWatcher = null;
        }
        $handle->stdin->close();
        $handle->stdout->close();
        $handle->stderr->close();
        foreach ($handle->sockets as $socket) {
            if (\is_resource($socket)) {
                @\fclose($socket);
            }
        }
        if (\is_resource($handle->wrapperStderrPipe)) {
            @\fclose($handle->wrapperStderrPipe);
        }
        if (\is_resource($handle->proc)) {
            \proc_close($handle->proc);
        }
    }
}
