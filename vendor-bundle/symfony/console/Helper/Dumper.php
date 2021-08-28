<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Helper;

use Phabel\Symfony\Component\Console\Output\OutputInterface;
use Phabel\Symfony\Component\VarDumper\Cloner\ClonerInterface;
use Phabel\Symfony\Component\VarDumper\Cloner\VarCloner;
use Phabel\Symfony\Component\VarDumper\Dumper\CliDumper;
/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
final class Dumper
{
    private $output;
    private $dumper;
    private $cloner;
    private $handler;
    public function __construct(OutputInterface $output, CliDumper $dumper = null, ClonerInterface $cloner = null)
    {
        $this->output = $output;
        $this->dumper = $dumper;
        $this->cloner = $cloner;
        if (\class_exists(CliDumper::class)) {
            $this->handler = function ($var) {
                $dumper = isset($this->dumper) ? $this->dumper : ($this->dumper = new CliDumper(null, null, CliDumper::DUMP_LIGHT_ARRAY | CliDumper::DUMP_COMMA_SEPARATOR));
                $dumper->setColors($this->output->isDecorated());
                $phabelReturn = \rtrim($dumper->dump(\Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->cloner) ? $this->cloner : ($this->cloner = new VarCloner()))->cloneVar($var)->withRefHandles(\false), \true));
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            };
        } else {
            $this->handler = function ($var) {
                switch (\true) {
                    case null === $var:
                        $phabelReturn = 'null';
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                        return $phabelReturn;
                    case \true === $var:
                        $phabelReturn = 'true';
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                        return $phabelReturn;
                    case \false === $var:
                        $phabelReturn = 'false';
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                        return $phabelReturn;
                    case \is_string($var):
                        $phabelReturn = '"' . $var . '"';
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                        return $phabelReturn;
                    default:
                        $phabelReturn = \rtrim(\print_r($var, \true));
                        if (!\is_string($phabelReturn)) {
                            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (string) $phabelReturn;
                            }
                        }
                        return $phabelReturn;
                }
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            };
        }
    }
    public function __invoke($var)
    {
        $phabel_14a91fe07c519d84 = $this->handler;
        $phabelReturn = $phabel_14a91fe07c519d84($var);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
