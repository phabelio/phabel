<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\String\Slugger;

use Phabel\Symfony\Component\String\AbstractUnicodeString;
use Phabel\Symfony\Component\String\UnicodeString;
use Phabel\Symfony\Contracts\Translation\LocaleAwareInterface;
if (!\interface_exists(LocaleAwareInterface::class)) {
    throw new \LogicException('You cannot use the "Symfony\\Component\\String\\Slugger\\AsciiSlugger" as the "symfony/translation-contracts" package is not installed. Try running "composer require symfony/translation-contracts".');
}
/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class AsciiSlugger implements SluggerInterface, LocaleAwareInterface
{
    const LOCALE_TO_TRANSLITERATOR_ID = ['am' => 'Amharic-Latin', 'ar' => 'Arabic-Latin', 'az' => 'Azerbaijani-Latin', 'be' => 'Belarusian-Latin', 'bg' => 'Bulgarian-Latin', 'bn' => 'Bengali-Latin', 'de' => 'de-ASCII', 'el' => 'Greek-Latin', 'fa' => 'Persian-Latin', 'he' => 'Hebrew-Latin', 'hy' => 'Armenian-Latin', 'ka' => 'Georgian-Latin', 'kk' => 'Kazakh-Latin', 'ky' => 'Kirghiz-Latin', 'ko' => 'Korean-Latin', 'mk' => 'Macedonian-Latin', 'mn' => 'Mongolian-Latin', 'or' => 'Oriya-Latin', 'ps' => 'Pashto-Latin', 'ru' => 'Russian-Latin', 'sr' => 'Serbian-Latin', 'sr_Cyrl' => 'Serbian-Latin', 'th' => 'Thai-Latin', 'tk' => 'Turkmen-Latin', 'uk' => 'Ukrainian-Latin', 'uz' => 'Uzbek-Latin', 'zh' => 'Han-Latin'];
    private $defaultLocale;
    private $symbolsMap = ['en' => ['@' => 'at', '&' => 'and']];
    /**
     * Cache of transliterators per locale.
     *
     * @var \Transliterator[]
     */
    private $transliterators = [];
    /**
     * @param array|\Closure|null $symbolsMap
     */
    public function __construct(string $defaultLocale = null, $symbolsMap = null)
    {
        if (null !== $symbolsMap && !\is_array($symbolsMap) && !$symbolsMap instanceof \Closure) {
            throw new \TypeError(\sprintf('Argument 2 passed to "%s()" must be array, Closure or null, "%s" given.', __METHOD__, \gettype($symbolsMap)));
        }
        $this->defaultLocale = $defaultLocale;
        $this->symbolsMap = $symbolsMap ?? $this->symbolsMap;
    }
    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->defaultLocale = $locale;
    }
    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->defaultLocale;
    }
    /**
     * {@inheritdoc}
     */
    public function slug(string $string, string $separator = '-', string $locale = null) : AbstractUnicodeString
    {
        $locale = $locale ?? $this->defaultLocale;
        $transliterator = [];
        if ($locale && ('de' === $locale || 0 === \Phabel\Target\Php71\Polyfill::strpos($locale, 'de_'))) {
            // Use the shortcut for German in UnicodeString::ascii() if possible (faster and no requirement on intl)
            $transliterator = ['de-ASCII'];
        } elseif (\function_exists('transliterator_transliterate') && $locale) {
            $transliterator = (array) $this->createTransliterator($locale);
        }
        if ($this->symbolsMap instanceof \Closure) {
            // If the symbols map is passed as a closure, there is no need to fallback to the parent locale
            // as the closure can just provide substitutions for all locales of interest.
            $symbolsMap = $this->symbolsMap;
            \Phabel\Target\Php73\Polyfill::array_unshift($transliterator, static function ($s) use($symbolsMap, $locale) {
                return $symbolsMap($s, $locale);
            });
        }
        $unicodeString = (new UnicodeString($string))->ascii($transliterator);
        if (\is_array($this->symbolsMap)) {
            $map = null;
            if (isset($this->symbolsMap[$locale])) {
                $map = $this->symbolsMap[$locale];
            } else {
                $parent = self::getParentLocale($locale);
                if ($parent && isset($this->symbolsMap[$parent])) {
                    $map = $this->symbolsMap[$parent];
                }
            }
            if ($map) {
                foreach ($map as $char => $replace) {
                    $unicodeString = $unicodeString->replace($char, ' ' . $replace . ' ');
                }
            }
        }
        return $unicodeString->replaceMatches('/[^A-Za-z0-9]++/', $separator)->trim($separator);
    }
    private function createTransliterator(string $locale)
    {
        if (\array_key_exists($locale, $this->transliterators)) {
            $phabelReturn = $this->transliterators[$locale];
            if (!($phabelReturn instanceof \Transliterator || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Transliterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        // Exact locale supported, cache and return
        if ($id = self::LOCALE_TO_TRANSLITERATOR_ID[$locale] ?? null) {
            $phabelReturn = $this->transliterators[$locale] = \Transliterator::create($id . '/BGN') ?? \Transliterator::create($id);
            if (!($phabelReturn instanceof \Transliterator || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Transliterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        // Locale not supported and no parent, fallback to any-latin
        if (!($parent = self::getParentLocale($locale))) {
            $phabelReturn = $this->transliterators[$locale] = null;
            if (!($phabelReturn instanceof \Transliterator || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Transliterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        // Try to use the parent locale (ie. try "de" for "de_AT") and cache both locales
        if ($id = self::LOCALE_TO_TRANSLITERATOR_ID[$parent] ?? null) {
            $transliterator = \Transliterator::create($id . '/BGN') ?? \Transliterator::create($id);
        }
        $phabelReturn = $this->transliterators[$locale] = $this->transliterators[$parent] = $transliterator ?? null;
        if (!($phabelReturn instanceof \Transliterator || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Transliterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private static function getParentLocale($locale)
    {
        if (!\is_null($locale)) {
            if (!\is_string($locale)) {
                if (!(\is_string($locale) || \Phabel\Target\Php72\Polyfill::is_object($locale) && \method_exists($locale, '__toString') || (\is_bool($locale) || \is_numeric($locale)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($locale) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($locale) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $locale = (string) $locale;
                }
            }
        }
        if (!$locale) {
            $phabelReturn = null;
            if (!\is_null($phabelReturn)) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            }
            return $phabelReturn;
        }
        if (\false === ($str = \strrchr($locale, '_'))) {
            $phabelReturn = null;
            if (!\is_null($phabelReturn)) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            }
            // no parent locale
            return $phabelReturn;
        }
        $phabelReturn = \Phabel\Target\Php80\Polyfill::substr($locale, 0, -\strlen($str));
        if (!\is_null($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
