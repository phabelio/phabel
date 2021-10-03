<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\Process\Exception;

use PhabelVendor\Symfony\Component\Process\Process;
/**
 * Exception for failed processes.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ProcessFailedException extends RuntimeException
{
    private $process;
    public function __construct(Process $process)
    {
        if ($process->isSuccessful()) {
            throw new InvalidArgumentException('Expected a failed process, but the given process was successful.');
        }
        $error = \sprintf('The command "%s" failed.

Exit Code: %s(%s)

Working directory: %s', $process->getCommandLine(), $process->getExitCode(), $process->getExitCodeText(), $process->getWorkingDirectory());
        if (!$process->isOutputDisabled()) {
            $error .= \sprintf("\n\nOutput:\n================\n%s\n\nError Output:\n================\n%s", $process->getOutput(), $process->getErrorOutput());
        }
        parent::__construct($error);
        $this->process = $process;
    }
    public function getProcess()
    {
        return $this->process;
    }
}
