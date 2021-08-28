<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Process\Pipes;

use Phabel\Symfony\Component\Process\Process;
/**
 * UnixPipes implementation uses unix pipes as handles.
 *
 * @author Romain Neutron <imprec@gmail.com>
 *
 * @internal
 */
class UnixPipes extends AbstractPipes
{
    private $ttyMode;
    private $ptyMode;
    private $haveReadSupport;
    public function __construct($ttyMode, $ptyMode, $input, $haveReadSupport)
    {
        if (!\is_null($ttyMode)) {
            if (!\is_bool($ttyMode)) {
                if (!(\is_bool($ttyMode) || \is_numeric($ttyMode) || \is_string($ttyMode))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($ttyMode) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ttyMode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $ttyMode = (bool) $ttyMode;
                }
            }
        }
        if (!\is_bool($ptyMode)) {
            if (!(\is_bool($ptyMode) || \is_numeric($ptyMode) || \is_string($ptyMode))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($ptyMode) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ptyMode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $ptyMode = (bool) $ptyMode;
            }
        }
        if (!\is_bool($haveReadSupport)) {
            if (!(\is_bool($haveReadSupport) || \is_numeric($haveReadSupport) || \is_string($haveReadSupport))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($haveReadSupport) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haveReadSupport) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $haveReadSupport = (bool) $haveReadSupport;
            }
        }
        $this->ttyMode = $ttyMode;
        $this->ptyMode = $ptyMode;
        $this->haveReadSupport = $haveReadSupport;
        parent::__construct($input);
    }
    /**
     * @return array
     */
    public function __sleep()
    {
        throw new \BadMethodCallException('Cannot serialize ' . __CLASS__);
    }
    public function __wakeup()
    {
        throw new \BadMethodCallException('Cannot unserialize ' . __CLASS__);
    }
    public function __destruct()
    {
        $this->close();
    }
    /**
     * {@inheritdoc}
     */
    public function getDescriptors()
    {
        if (!$this->haveReadSupport) {
            $nullstream = \fopen('/dev/null', 'c');
            $phabelReturn = [['pipe', 'r'], $nullstream, $nullstream];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->ttyMode) {
            $phabelReturn = [['file', '/dev/tty', 'r'], ['file', '/dev/tty', 'w'], ['file', '/dev/tty', 'w']];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->ptyMode && Process::isPtySupported()) {
            $phabelReturn = [['pty'], ['pty'], ['pty']];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = [
            ['pipe', 'r'],
            ['pipe', 'w'],
            // stdout
            ['pipe', 'w'],
        ];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function getFiles()
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function readAndWrite($blocking, $close = \false)
    {
        if (!\is_bool($blocking)) {
            if (!(\is_bool($blocking) || \is_numeric($blocking) || \is_string($blocking))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($blocking) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($blocking) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $blocking = (bool) $blocking;
            }
        }
        if (!\is_bool($close)) {
            if (!(\is_bool($close) || \is_numeric($close) || \is_string($close))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($close) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($close) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $close = (bool) $close;
            }
        }
        $this->unblock();
        $w = $this->write();
        $read = $e = [];
        $r = $this->pipes;
        unset($r[0]);
        // let's have a look if something changed in streams
        \set_error_handler([$this, 'handleError']);
        if (($r || $w) && \false === \stream_select($r, $w, $e, 0, $blocking ? Process::TIMEOUT_PRECISION * 1000000.0 : 0)) {
            \restore_error_handler();
            // if a system call has been interrupted, forget about it, let's try again
            // otherwise, an error occurred, let's reset pipes
            if (!$this->hasSystemCallBeenInterrupted()) {
                $this->pipes = [];
            }
            $phabelReturn = $read;
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        \restore_error_handler();
        foreach ($r as $pipe) {
            // prior PHP 5.4 the array passed to stream_select is modified and
            // lose key association, we have to find back the key
            $read[$type = \array_search($pipe, $this->pipes, \true)] = '';
            do {
                $data = @\fread($pipe, self::CHUNK_SIZE);
                $read[$type] .= $data;
            } while (isset($data[0]) && ($close || isset($data[self::CHUNK_SIZE - 1])));
            if (!isset($read[$type][0])) {
                unset($read[$type]);
            }
            if ($close && \feof($pipe)) {
                \fclose($pipe);
                unset($this->pipes[$type]);
            }
        }
        $phabelReturn = $read;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function haveReadSupport()
    {
        $phabelReturn = $this->haveReadSupport;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function areOpen()
    {
        $phabelReturn = (bool) $this->pipes;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
