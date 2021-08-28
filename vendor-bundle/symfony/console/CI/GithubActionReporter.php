<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\CI;

use Phabel\Symfony\Component\Console\Output\OutputInterface;
/**
 * Utility class for Github actions.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class GithubActionReporter
{
    private $output;
    /**
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L80-L85
     */
    const ESCAPED_DATA = ['%' => '%25', "\r" => '%0D', "\n" => '%0A'];
    /**
     * @see https://github.com/actions/toolkit/blob/5e5e1b7aacba68a53836a34db4a288c3c1c1585b/packages/core/src/command.ts#L87-L94
     */
    const ESCAPED_PROPERTIES = ['%' => '%25', "\r" => '%0D', "\n" => '%0A', ':' => '%3A', ',' => '%2C'];
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
    public static function isGithubActionEnvironment()
    {
        $phabelReturn = \false !== \getenv('GITHUB_ACTIONS');
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
     * Output an error using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-an-error-message
     */
    public function error($message, $file = null, $line = null, $col = null)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_null($file)) {
            if (!\is_string($file)) {
                if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($file) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $file = (string) $file;
                }
            }
        }
        if (!\is_null($line)) {
            if (!\is_int($line)) {
                if (!(\is_bool($line) || \is_numeric($line))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($line) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $line = (int) $line;
                }
            }
        }
        if (!\is_null($col)) {
            if (!\is_int($col)) {
                if (!(\is_bool($col) || \is_numeric($col))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($col) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($col) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $col = (int) $col;
                }
            }
        }
        $this->log('error', $message, $file, $line, $col);
    }
    /**
     * Output a warning using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-warning-message
     */
    public function warning($message, $file = null, $line = null, $col = null)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_null($file)) {
            if (!\is_string($file)) {
                if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($file) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $file = (string) $file;
                }
            }
        }
        if (!\is_null($line)) {
            if (!\is_int($line)) {
                if (!(\is_bool($line) || \is_numeric($line))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($line) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $line = (int) $line;
                }
            }
        }
        if (!\is_null($col)) {
            if (!\is_int($col)) {
                if (!(\is_bool($col) || \is_numeric($col))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($col) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($col) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $col = (int) $col;
                }
            }
        }
        $this->log('warning', $message, $file, $line, $col);
    }
    /**
     * Output a debug log using the Github annotations format.
     *
     * @see https://docs.github.com/en/free-pro-team@latest/actions/reference/workflow-commands-for-github-actions#setting-a-debug-message
     */
    public function debug($message, $file = null, $line = null, $col = null)
    {
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_null($file)) {
            if (!\is_string($file)) {
                if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($file) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $file = (string) $file;
                }
            }
        }
        if (!\is_null($line)) {
            if (!\is_int($line)) {
                if (!(\is_bool($line) || \is_numeric($line))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($line) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $line = (int) $line;
                }
            }
        }
        if (!\is_null($col)) {
            if (!\is_int($col)) {
                if (!(\is_bool($col) || \is_numeric($col))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($col) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($col) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $col = (int) $col;
                }
            }
        }
        $this->log('debug', $message, $file, $line, $col);
    }
    private function log($type, $message, $file = null, $line = null, $col = null)
    {
        if (!\is_string($type)) {
            if (!(\is_string($type) || \is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $type = (string) $type;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        if (!\is_null($file)) {
            if (!\is_string($file)) {
                if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($file) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $file = (string) $file;
                }
            }
        }
        if (!\is_null($line)) {
            if (!\is_int($line)) {
                if (!(\is_bool($line) || \is_numeric($line))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($line) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($line) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $line = (int) $line;
                }
            }
        }
        if (!\is_null($col)) {
            if (!\is_int($col)) {
                if (!(\is_bool($col) || \is_numeric($col))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($col) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($col) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $col = (int) $col;
                }
            }
        }
        // Some values must be encoded.
        $message = \strtr($message, self::ESCAPED_DATA);
        if (!$file) {
            // No file provided, output the message solely:
            $this->output->writeln(\sprintf('::%s::%s', $type, $message));
            return;
        }
        $this->output->writeln(\sprintf('::%s file=%s,line=%s,col=%s::%s', $type, \strtr($file, self::ESCAPED_PROPERTIES), \strtr(isset($line) ? $line : 1, self::ESCAPED_PROPERTIES), \strtr(isset($col) ? $col : 0, self::ESCAPED_PROPERTIES), $message));
    }
}
