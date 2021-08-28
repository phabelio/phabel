<?php

namespace Phabel\Amp\Process\Internal\Posix;

use Phabel\Amp\ByteStream\ResourceInputStream;
use Phabel\Amp\ByteStream\ResourceOutputStream;
use Phabel\Amp\Deferred;
use Phabel\Amp\Loop;
use Phabel\Amp\Process\Internal\ProcessHandle;
use Phabel\Amp\Process\Internal\ProcessRunner;
use Phabel\Amp\Process\Internal\ProcessStatus;
use Phabel\Amp\Process\ProcessException;
use Phabel\Amp\Process\ProcessInputStream;
use Phabel\Amp\Process\ProcessOutputStream;
use Phabel\Amp\Promise;
/** @internal */
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
    /** @var string|null */
    private static $fdPath;
    public static function onProcessEndExtraDataPipeReadable($watcher, $stream, Handle $handle)
    {
        Loop::cancel($watcher);
        $handle->extraDataPipeWatcher = null;
        $handle->status = ProcessStatus::ENDED;
        if (!\is_resource($stream) || \feof($stream)) {
            $handle->joinDeferred->fail(new ProcessException("Process ended unexpectedly"));
        } else {
            $handle->joinDeferred->resolve((int) \rtrim(@\stream_get_contents($stream)));
        }
    }
    public static function onProcessStartExtraDataPipeReadable($watcher, $stream, $data)
    {
        Loop::cancel($watcher);
        $pid = \rtrim(@\fgets($stream));
        /** @var $deferreds Deferred[] */
        list($handle, $pipes, $deferreds) = $data;
        if (!$pid || !\is_numeric($pid)) {
            $error = new ProcessException("Could not determine PID");
            $handle->pidDeferred->fail($error);
            foreach ($deferreds as $deferred) {
                /** @var $deferred Deferred */
                $deferred->fail($error);
            }
            if ($handle->status < ProcessStatus::ENDED) {
                $handle->status = ProcessStatus::ENDED;
                $handle->joinDeferred->fail($error);
            }
            return;
        }
        $handle->status = ProcessStatus::RUNNING;
        $handle->pidDeferred->resolve((int) $pid);
        $deferreds[0]->resolve($pipes[0]);
        $deferreds[1]->resolve($pipes[1]);
        $deferreds[2]->resolve($pipes[2]);
        if ($handle->extraDataPipeWatcher !== null) {
            Loop::enable($handle->extraDataPipeWatcher);
        }
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
        $command = \sprintf('{ (%s) <&3 3<&- 3>/dev/null & } 3<&0; trap "" INT TERM QUIT HUP;pid=$!; echo $pid >&3; wait $pid; RC=$?; echo $RC >&3; exit $RC', $command);
        $handle = new Handle();
        $handle->proc = @\proc_open($command, $this->generateFds(), $pipes, $cwd ?: null, $env ?: null, $options);
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
        $stdinDeferred = new Deferred();
        $handle->stdin = new ProcessOutputStream($stdinDeferred->promise());
        $stdoutDeferred = new Deferred();
        $handle->stdout = new ProcessInputStream($stdoutDeferred->promise());
        $stderrDeferred = new Deferred();
        $handle->stderr = new ProcessInputStream($stderrDeferred->promise());
        $handle->extraDataPipe = $pipes[3];
        \stream_set_blocking($pipes[3], \false);
        $handle->extraDataPipeStartWatcher = Loop::onReadable($pipes[3], [self::class, 'onProcessStartExtraDataPipeReadable'], [$handle, [new ResourceOutputStream($pipes[0]), new ResourceInputStream($pipes[1]), new ResourceInputStream($pipes[2])], [$stdinDeferred, $stdoutDeferred, $stderrDeferred]]);
        $handle->extraDataPipeWatcher = Loop::onReadable($pipes[3], [self::class, 'onProcessEndExtraDataPipeReadable'], $handle);
        Loop::unreference($handle->extraDataPipeWatcher);
        Loop::disable($handle->extraDataPipeWatcher);
        $phabelReturn = $handle;
        if (!$phabelReturn instanceof ProcessHandle) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ProcessHandle, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function generateFds()
    {
        if (self::$fdPath === null) {
            self::$fdPath = \file_exists("/dev/fd") ? "/dev/fd" : "/proc/self/fd";
        }
        $fdList = @\scandir(self::$fdPath, \SCANDIR_SORT_NONE);
        if ($fdList === \false) {
            throw new ProcessException("Unable to list open file descriptors");
        }
        $fdList = \array_filter($fdList, function ($path) {
            if (!\is_string($path)) {
                if (!(\is_string($path) || \is_object($path) && \method_exists($path, '__toString') || (\is_bool($path) || \is_numeric($path)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($path) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($path) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $path = (string) $path;
                }
            }
            $phabelReturn = $path !== "." && $path !== "..";
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        });
        $fds = [];
        foreach ($fdList as $id) {
            $fds[(int) $id] = ["file", "/dev/null", "r"];
        }
        $phabelReturn = self::FD_SPEC + $fds;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function join(ProcessHandle $handle)
    {
        /** @var Handle $handle */
        if ($handle->extraDataPipeWatcher !== null) {
            Loop::reference($handle->extraDataPipeWatcher);
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
        if ($handle->extraDataPipeWatcher !== null) {
            Loop::cancel($handle->extraDataPipeWatcher);
            $handle->extraDataPipeWatcher = null;
        }
        /** @var Handle $handle */
        if ($handle->extraDataPipeStartWatcher !== null) {
            Loop::cancel($handle->extraDataPipeStartWatcher);
            $handle->extraDataPipeStartWatcher = null;
        }
        if (!\proc_terminate($handle->proc, 9)) {
            // Forcefully kill the process using SIGKILL.
            throw new ProcessException("Terminating process failed");
        }
        $handle->pidDeferred->promise()->onResolve(function ($error, $pid) {
            // The function should not call posix_kill() if $pid is null (i.e., there was an error starting the process).
            if ($error) {
                return;
            }
            // ignore errors because process not always detached
            @\posix_kill($pid, 9);
        });
        if ($handle->status < ProcessStatus::ENDED) {
            $handle->status = ProcessStatus::ENDED;
            $handle->joinDeferred->fail(new ProcessException("The process was killed"));
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
        $handle->pidDeferred->promise()->onResolve(function ($error, $pid) use($signo) {
            if ($error) {
                return;
            }
            @\posix_kill($pid, $signo);
        });
    }
    /** @inheritdoc */
    public function destroy(ProcessHandle $handle)
    {
        /** @var Handle $handle */
        if ($handle->status < ProcessStatus::ENDED && \getmypid() === $handle->originalParentPid) {
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
        /** @var Handle $handle */
        if ($handle->extraDataPipeWatcher !== null) {
            Loop::cancel($handle->extraDataPipeWatcher);
            $handle->extraDataPipeWatcher = null;
        }
        /** @var Handle $handle */
        if ($handle->extraDataPipeStartWatcher !== null) {
            Loop::cancel($handle->extraDataPipeStartWatcher);
            $handle->extraDataPipeStartWatcher = null;
        }
        if (\is_resource($handle->extraDataPipe)) {
            \fclose($handle->extraDataPipe);
        }
        $handle->stdin->close();
        $handle->stdout->close();
        $handle->stderr->close();
        if (\is_resource($handle->proc)) {
            \proc_close($handle->proc);
        }
    }
}
