<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\Console\Formatter;

use Phabel\Symfony\Component\Console\Color;
/**
 * Formatter style class for defining styles.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class OutputFormatterStyle implements OutputFormatterStyleInterface
{
    private $color;
    private $foreground;
    private $background;
    private $options;
    private $href;
    private $handlesHrefGracefully;
    /**
     * Initializes output formatter style.
     *
     * @param string|null $foreground The style foreground color name
     * @param string|null $background The style background color name
     */
    public function __construct($foreground = null, $background = null, array $options = [])
    {
        if (!\is_null($foreground)) {
            if (!\is_string($foreground)) {
                if (!(\is_string($foreground) || \is_object($foreground) && \method_exists($foreground, '__toString') || (\is_bool($foreground) || \is_numeric($foreground)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($foreground) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($foreground) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $foreground = (string) $foreground;
                }
            }
        }
        if (!\is_null($background)) {
            if (!\is_string($background)) {
                if (!(\is_string($background) || \is_object($background) && \method_exists($background, '__toString') || (\is_bool($background) || \is_numeric($background)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($background) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($background) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $background = (string) $background;
                }
            }
        }
        $this->color = new Color($this->foreground = $foreground ?: '', $this->background = $background ?: '', $this->options = $options);
    }
    /**
     * {@inheritdoc}
     */
    public function setForeground($color = null)
    {
        if (!\is_null($color)) {
            if (!\is_string($color)) {
                if (!(\is_string($color) || \is_object($color) && \method_exists($color, '__toString') || (\is_bool($color) || \is_numeric($color)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $color = (string) $color;
                }
            }
        }
        $this->color = new Color($this->foreground = $color ?: '', $this->background, $this->options);
    }
    /**
     * {@inheritdoc}
     */
    public function setBackground($color = null)
    {
        if (!\is_null($color)) {
            if (!\is_string($color)) {
                if (!(\is_string($color) || \is_object($color) && \method_exists($color, '__toString') || (\is_bool($color) || \is_numeric($color)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($color) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($color) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $color = (string) $color;
                }
            }
        }
        $this->color = new Color($this->foreground, $this->background = $color ?: '', $this->options);
    }
    public function setHref($url)
    {
        if (!\is_string($url)) {
            if (!(\is_string($url) || \is_object($url) && \method_exists($url, '__toString') || (\is_bool($url) || \is_numeric($url)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($url) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($url) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $url = (string) $url;
            }
        }
        $this->href = $url;
    }
    /**
     * {@inheritdoc}
     */
    public function setOption($option)
    {
        if (!\is_string($option)) {
            if (!(\is_string($option) || \is_object($option) && \method_exists($option, '__toString') || (\is_bool($option) || \is_numeric($option)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($option) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($option) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $option = (string) $option;
            }
        }
        $this->options[] = $option;
        $this->color = new Color($this->foreground, $this->background, $this->options);
    }
    /**
     * {@inheritdoc}
     */
    public function unsetOption($option)
    {
        if (!\is_string($option)) {
            if (!(\is_string($option) || \is_object($option) && \method_exists($option, '__toString') || (\is_bool($option) || \is_numeric($option)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($option) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($option) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $option = (string) $option;
            }
        }
        $pos = \array_search($option, $this->options);
        if (\false !== $pos) {
            unset($this->options[$pos]);
        }
        $this->color = new Color($this->foreground, $this->background, $this->options);
    }
    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->color = new Color($this->foreground, $this->background, $this->options = $options);
    }
    /**
     * {@inheritdoc}
     */
    public function apply($text)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $text = (string) $text;
            }
        }
        if (null === $this->handlesHrefGracefully) {
            $this->handlesHrefGracefully = 'JetBrains-JediTerm' !== \getenv('TERMINAL_EMULATOR') && (!\getenv('KONSOLE_VERSION') || (int) \getenv('KONSOLE_VERSION') > 201100);
        }
        if (null !== $this->href && $this->handlesHrefGracefully) {
            $text = "\x1b]8;;{$this->href}\x1b\\{$text}\x1b]8;;\x1b\\";
        }
        return $this->color->apply($text);
    }
}
