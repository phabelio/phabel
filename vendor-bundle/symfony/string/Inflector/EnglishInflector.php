<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhabelVendor\Symfony\Component\String\Inflector;

final class EnglishInflector implements InflectorInterface
{
    /**
     * Map English plural to singular suffixes.
     *
     * @see http://english-zone.com/spelling/plurals.html
     */
    private const PLURAL_MAP = array(0 => array(0 => 'a', 1 => 1, 2 => \true, 3 => \true, 4 => array(0 => 'on', 1 => 'um')), 1 => array(0 => 'ea', 1 => 2, 2 => \true, 3 => \true, 4 => 'a'), 2 => array(0 => 'secivres', 1 => 8, 2 => \true, 3 => \true, 4 => 'service'), 3 => array(0 => 'eci', 1 => 3, 2 => \false, 3 => \true, 4 => 'ouse'), 4 => array(0 => 'esee', 1 => 4, 2 => \false, 3 => \true, 4 => 'oose'), 5 => array(0 => 'i', 1 => 1, 2 => \true, 3 => \true, 4 => 'us'), 6 => array(0 => 'nem', 1 => 3, 2 => \true, 3 => \true, 4 => 'man'), 7 => array(0 => 'nerdlihc', 1 => 8, 2 => \true, 3 => \true, 4 => 'child'), 8 => array(0 => 'nexo', 1 => 4, 2 => \false, 3 => \false, 4 => 'ox'), 9 => array(0 => 'seci', 1 => 4, 2 => \false, 3 => \true, 4 => array(0 => 'ex', 1 => 'ix', 2 => 'ice')), 10 => array(0 => 'seifles', 1 => 7, 2 => \true, 3 => \true, 4 => 'selfie'), 11 => array(0 => 'seibmoz', 1 => 7, 2 => \true, 3 => \true, 4 => 'zombie'), 12 => array(0 => 'seivom', 1 => 6, 2 => \true, 3 => \true, 4 => 'movie'), 13 => array(0 => 'sesutcep', 1 => 8, 2 => \true, 3 => \true, 4 => 'pectus'), 14 => array(0 => 'teef', 1 => 4, 2 => \true, 3 => \true, 4 => 'foot'), 15 => array(0 => 'eseeg', 1 => 5, 2 => \true, 3 => \true, 4 => 'goose'), 16 => array(0 => 'hteet', 1 => 5, 2 => \true, 3 => \true, 4 => 'tooth'), 17 => array(0 => 'swen', 1 => 4, 2 => \true, 3 => \true, 4 => 'news'), 18 => array(0 => 'seires', 1 => 6, 2 => \true, 3 => \true, 4 => 'series'), 19 => array(0 => 'sei', 1 => 3, 2 => \false, 3 => \true, 4 => 'y'), 20 => array(0 => 'sess', 1 => 4, 2 => \true, 3 => \false, 4 => 'ss'), 21 => array(0 => 'ses', 1 => 3, 2 => \true, 3 => \true, 4 => array(0 => 's', 1 => 'se', 2 => 'sis')), 22 => array(0 => 'sevit', 1 => 5, 2 => \true, 3 => \true, 4 => 'tive'), 23 => array(0 => 'sevird', 1 => 6, 2 => \false, 3 => \true, 4 => 'drive'), 24 => array(0 => 'sevi', 1 => 4, 2 => \false, 3 => \true, 4 => 'ife'), 25 => array(0 => 'sevom', 1 => 5, 2 => \true, 3 => \true, 4 => 'move'), 26 => array(0 => 'sev', 1 => 3, 2 => \true, 3 => \true, 4 => array(0 => 'f', 1 => 've', 2 => 'ff')), 27 => array(0 => 'sexa', 1 => 4, 2 => \false, 3 => \false, 4 => array(0 => 'ax', 1 => 'axe', 2 => 'axis')), 28 => array(0 => 'sex', 1 => 3, 2 => \true, 3 => \false, 4 => 'x'), 29 => array(0 => 'sezz', 1 => 4, 2 => \true, 3 => \false, 4 => 'z'), 30 => array(0 => 'suae', 1 => 4, 2 => \false, 3 => \true, 4 => 'eau'), 31 => array(0 => 'see', 1 => 3, 2 => \true, 3 => \true, 4 => 'ee'), 32 => array(0 => 'segd', 1 => 4, 2 => \true, 3 => \true, 4 => 'dge'), 33 => array(0 => 'se', 1 => 2, 2 => \true, 3 => \true, 4 => array(0 => '', 1 => 'e')), 34 => array(0 => 's', 1 => 1, 2 => \true, 3 => \true, 4 => ''), 35 => array(0 => 'xuae', 1 => 4, 2 => \false, 3 => \true, 4 => 'eau'), 36 => array(0 => 'elpoep', 1 => 6, 2 => \true, 3 => \true, 4 => 'person'));
    /**
     * Map English singular to plural suffixes.
     *
     * @see http://english-zone.com/spelling/plurals.html
     */
    private const SINGULAR_MAP = array(0 => array(0 => 'airetirc', 1 => 8, 2 => \false, 3 => \false, 4 => 'criterion'), 1 => array(0 => 'aluben', 1 => 6, 2 => \false, 3 => \false, 4 => 'nebulae'), 2 => array(0 => 'dlihc', 1 => 5, 2 => \true, 3 => \true, 4 => 'children'), 3 => array(0 => 'eci', 1 => 3, 2 => \false, 3 => \true, 4 => 'ices'), 4 => array(0 => 'ecivres', 1 => 7, 2 => \true, 3 => \true, 4 => 'services'), 5 => array(0 => 'efi', 1 => 3, 2 => \false, 3 => \true, 4 => 'ives'), 6 => array(0 => 'eifles', 1 => 6, 2 => \true, 3 => \true, 4 => 'selfies'), 7 => array(0 => 'eivom', 1 => 5, 2 => \true, 3 => \true, 4 => 'movies'), 8 => array(0 => 'esuol', 1 => 5, 2 => \false, 3 => \true, 4 => 'lice'), 9 => array(0 => 'esuom', 1 => 5, 2 => \false, 3 => \true, 4 => 'mice'), 10 => array(0 => 'esoo', 1 => 4, 2 => \false, 3 => \true, 4 => 'eese'), 11 => array(0 => 'es', 1 => 2, 2 => \true, 3 => \true, 4 => 'ses'), 12 => array(0 => 'esoog', 1 => 5, 2 => \true, 3 => \true, 4 => 'geese'), 13 => array(0 => 'ev', 1 => 2, 2 => \true, 3 => \true, 4 => 'ves'), 14 => array(0 => 'evird', 1 => 5, 2 => \false, 3 => \true, 4 => 'drives'), 15 => array(0 => 'evit', 1 => 4, 2 => \true, 3 => \true, 4 => 'tives'), 16 => array(0 => 'evom', 1 => 4, 2 => \true, 3 => \true, 4 => 'moves'), 17 => array(0 => 'ffats', 1 => 5, 2 => \true, 3 => \true, 4 => 'staves'), 18 => array(0 => 'ff', 1 => 2, 2 => \true, 3 => \true, 4 => 'ffs'), 19 => array(0 => 'f', 1 => 1, 2 => \true, 3 => \true, 4 => array(0 => 'fs', 1 => 'ves')), 20 => array(0 => 'hc', 1 => 2, 2 => \true, 3 => \true, 4 => 'ches'), 21 => array(0 => 'hs', 1 => 2, 2 => \true, 3 => \true, 4 => 'shes'), 22 => array(0 => 'htoot', 1 => 5, 2 => \true, 3 => \true, 4 => 'teeth'), 23 => array(0 => 'mu', 1 => 2, 2 => \true, 3 => \true, 4 => 'a'), 24 => array(0 => 'nam', 1 => 3, 2 => \true, 3 => \true, 4 => 'men'), 25 => array(0 => 'nosrep', 1 => 6, 2 => \true, 3 => \true, 4 => array(0 => 'persons', 1 => 'people')), 26 => array(0 => 'noi', 1 => 3, 2 => \true, 3 => \true, 4 => 'ions'), 27 => array(0 => 'nop', 1 => 3, 2 => \true, 3 => \true, 4 => 'pons'), 28 => array(0 => 'nos', 1 => 3, 2 => \true, 3 => \true, 4 => 'sons'), 29 => array(0 => 'no', 1 => 2, 2 => \true, 3 => \true, 4 => 'a'), 30 => array(0 => 'ohce', 1 => 4, 2 => \true, 3 => \true, 4 => 'echoes'), 31 => array(0 => 'oreh', 1 => 4, 2 => \true, 3 => \true, 4 => 'heroes'), 32 => array(0 => 'salta', 1 => 5, 2 => \true, 3 => \true, 4 => 'atlases'), 33 => array(0 => 'siri', 1 => 4, 2 => \true, 3 => \true, 4 => 'irises'), 34 => array(0 => 'sis', 1 => 3, 2 => \true, 3 => \true, 4 => 'ses'), 35 => array(0 => 'ss', 1 => 2, 2 => \true, 3 => \false, 4 => 'sses'), 36 => array(0 => 'suballys', 1 => 8, 2 => \true, 3 => \true, 4 => 'syllabi'), 37 => array(0 => 'sub', 1 => 3, 2 => \true, 3 => \true, 4 => 'buses'), 38 => array(0 => 'suc', 1 => 3, 2 => \true, 3 => \true, 4 => 'cuses'), 39 => array(0 => 'sutcep', 1 => 6, 2 => \true, 3 => \true, 4 => 'pectuses'), 40 => array(0 => 'su', 1 => 2, 2 => \true, 3 => \true, 4 => 'i'), 41 => array(0 => 'swen', 1 => 4, 2 => \true, 3 => \true, 4 => 'news'), 42 => array(0 => 'toof', 1 => 4, 2 => \true, 3 => \true, 4 => 'feet'), 43 => array(0 => 'uae', 1 => 3, 2 => \false, 3 => \true, 4 => array(0 => 'eaus', 1 => 'eaux')), 44 => array(0 => 'xo', 1 => 2, 2 => \false, 3 => \false, 4 => 'oxen'), 45 => array(0 => 'xaoh', 1 => 4, 2 => \true, 3 => \false, 4 => 'hoaxes'), 46 => array(0 => 'xedni', 1 => 5, 2 => \false, 3 => \true, 4 => array(0 => 'indicies', 1 => 'indexes')), 47 => array(0 => 'xo', 1 => 2, 2 => \false, 3 => \true, 4 => 'oxes'), 48 => array(0 => 'x', 1 => 1, 2 => \true, 3 => \false, 4 => array(0 => 'cies', 1 => 'xes')), 49 => array(0 => 'xi', 1 => 2, 2 => \false, 3 => \true, 4 => 'ices'), 50 => array(0 => 'y', 1 => 1, 2 => \false, 3 => \true, 4 => 'ies'), 51 => array(0 => 'ziuq', 1 => 4, 2 => \true, 3 => \false, 4 => 'quizzes'), 52 => array(0 => 'z', 1 => 1, 2 => \true, 3 => \true, 4 => 'zes'));
    /**
     * A list of words which should not be inflected, reversed.
     */
    private const UNINFLECTED = array(0 => '', 1 => 'atad', 2 => 'reed', 3 => 'kcabdeef', 4 => 'hsif', 5 => 'ofni', 6 => 'esoom', 7 => 'seires', 8 => 'peehs', 9 => 'seiceps');
    /**
     * {@inheritdoc}
     */
    public function singularize(string $plural) : array
    {
        $pluralRev = \strrev($plural);
        $lowerPluralRev = \strtolower($pluralRev);
        $pluralLength = \strlen($lowerPluralRev);
        // Check if the word is one which is not inflected, return early if so
        if (\in_array($lowerPluralRev, self::UNINFLECTED, \true)) {
            return [$plural];
        }
        // The outer loop iterates over the entries of the plural table
        // The inner loop $j iterates over the characters of the plural suffix
        // in the plural table to compare them with the characters of the actual
        // given plural suffix
        foreach (self::PLURAL_MAP as $map) {
            $suffix = $map[0];
            $suffixLength = $map[1];
            $j = 0;
            // Compare characters in the plural table and of the suffix of the
            // given plural one by one
            while ($suffix[$j] === $lowerPluralRev[$j]) {
                // Let $j point to the next character
                ++$j;
                // Successfully compared the last character
                // Add an entry with the singular suffix to the singular array
                if ($j === $suffixLength) {
                    // Is there any character preceding the suffix in the plural string?
                    if ($j < $pluralLength) {
                        $nextIsVocal = \false !== \strpos('aeiou', $lowerPluralRev[$j]);
                        if (!$map[2] && $nextIsVocal) {
                            // suffix may not succeed a vocal but next char is one
                            break;
                        }
                        if (!$map[3] && !$nextIsVocal) {
                            // suffix may not succeed a consonant but next char is one
                            break;
                        }
                    }
                    $newBase = \Phabel\Target\Php80\Polyfill::substr($plural, 0, $pluralLength - $suffixLength);
                    $newSuffix = $map[4];
                    // Check whether the first character in the plural suffix
                    // is uppercased. If yes, uppercase the first character in
                    // the singular suffix too
                    $firstUpper = \ctype_upper($pluralRev[$j - 1]);
                    if (\is_array($newSuffix)) {
                        $singulars = [];
                        foreach ($newSuffix as $newSuffixEntry) {
                            $singulars[] = $newBase . ($firstUpper ? \ucfirst($newSuffixEntry) : $newSuffixEntry);
                        }
                        return $singulars;
                    }
                    return [$newBase . ($firstUpper ? \ucfirst($newSuffix) : $newSuffix)];
                }
                // Suffix is longer than word
                if ($j === $pluralLength) {
                    break;
                }
            }
        }
        // Assume that plural and singular is identical
        return [$plural];
    }
    /**
     * {@inheritdoc}
     */
    public function pluralize(string $singular) : array
    {
        $singularRev = \strrev($singular);
        $lowerSingularRev = \strtolower($singularRev);
        $singularLength = \strlen($lowerSingularRev);
        // Check if the word is one which is not inflected, return early if so
        if (\in_array($lowerSingularRev, self::UNINFLECTED, \true)) {
            return [$singular];
        }
        // The outer loop iterates over the entries of the singular table
        // The inner loop $j iterates over the characters of the singular suffix
        // in the singular table to compare them with the characters of the actual
        // given singular suffix
        foreach (self::SINGULAR_MAP as $map) {
            $suffix = $map[0];
            $suffixLength = $map[1];
            $j = 0;
            // Compare characters in the singular table and of the suffix of the
            // given plural one by one
            while ($suffix[$j] === $lowerSingularRev[$j]) {
                // Let $j point to the next character
                ++$j;
                // Successfully compared the last character
                // Add an entry with the plural suffix to the plural array
                if ($j === $suffixLength) {
                    // Is there any character preceding the suffix in the plural string?
                    if ($j < $singularLength) {
                        $nextIsVocal = \false !== \strpos('aeiou', $lowerSingularRev[$j]);
                        if (!$map[2] && $nextIsVocal) {
                            // suffix may not succeed a vocal but next char is one
                            break;
                        }
                        if (!$map[3] && !$nextIsVocal) {
                            // suffix may not succeed a consonant but next char is one
                            break;
                        }
                    }
                    $newBase = \Phabel\Target\Php80\Polyfill::substr($singular, 0, $singularLength - $suffixLength);
                    $newSuffix = $map[4];
                    // Check whether the first character in the singular suffix
                    // is uppercased. If yes, uppercase the first character in
                    // the singular suffix too
                    $firstUpper = \ctype_upper($singularRev[$j - 1]);
                    if (\is_array($newSuffix)) {
                        $plurals = [];
                        foreach ($newSuffix as $newSuffixEntry) {
                            $plurals[] = $newBase . ($firstUpper ? \ucfirst($newSuffixEntry) : $newSuffixEntry);
                        }
                        return $plurals;
                    }
                    return [$newBase . ($firstUpper ? \ucfirst($newSuffix) : $newSuffix)];
                }
                // Suffix is longer than word
                if ($j === $singularLength) {
                    break;
                }
            }
        }
        // Assume that plural is singular with a trailing `s`
        return [$singular . 's'];
    }
}
