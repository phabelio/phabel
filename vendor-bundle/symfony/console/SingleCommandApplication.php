<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console;

use Phabel\Symfony\Component\Console\Command\Command;
use Phabel\Symfony\Component\Console\Input\InputInterface;
use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Grégoire Pineau <lyrixx@lyrixx.info>
 */
class SingleCommandApplication extends Command
{
    private $version = 'UNKNOWN';
    private $autoExit = \true;
    private $running = \false;
    public function setVersion($version)
    {
        if (!\is_string($version)) {
            if (!(\is_string($version) || \is_object($version) && \method_exists($version, '__toString') || (\is_bool($version) || \is_numeric($version)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($version) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($version) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $version = (string) $version;
            }
        }
        $this->version = $version;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @final
     */
    public function setAutoExit($autoExit)
    {
        if (!\is_bool($autoExit)) {
            if (!(\is_bool($autoExit) || \is_numeric($autoExit) || \is_string($autoExit))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($autoExit) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($autoExit) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $autoExit = (bool) $autoExit;
            }
        }
        $this->autoExit = $autoExit;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if ($this->running) {
            $phabelReturn = parent::run($input, $output);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        // We use the command name as the application name
        $application = new Application($this->getName() ?: 'UNKNOWN', $this->version);
        $application->setAutoExit($this->autoExit);
        // Fix the usage of the command displayed with "--help"
        $this->setName($_SERVER['argv'][0]);
        $application->add($this);
        $application->setDefaultCommand($this->getName(), \true);
        $this->running = \true;
        try {
            $ret = $application->run($input, $output);
        } finally {
            $this->running = \false;
        }
        $phabelReturn = isset($ret) ? $ret : 1;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
