<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\String\Inflector;

/**
 * French inflector.
 *
 * This class does only inflect nouns; not adjectives nor composed words like "soixante-dix".
 */
final class FrenchInflector implements InflectorInterface
{
    /**
     * A list of all rules for pluralise.
     *
     * @see https://la-conjugaison.nouvelobs.com/regles/grammaire/le-pluriel-des-noms-121.php
     */
    const PLURALIZE_REGEXP = [
        // First entry: regexp
        // Second entry: replacement
        // Words finishing with "s", "x" or "z" are invariables
        // Les mots finissant par "s", "x" ou "z" sont invariables
        ['/(s|x|z)$/i', '\\1'],
        // Words finishing with "eau" are pluralized with a "x"
        // Les mots finissant par "eau" prennent tous un "x" au pluriel
        ['/(eau)$/i', '\\1x'],
        // Words finishing with "au" are pluralized with a "x" excepted "landau"
        // Les mots finissant par "au" prennent un "x" au pluriel sauf "landau"
        ['/^(landau)$/i', '\\1s'],
        ['/(au)$/i', '\\1x'],
        // Words finishing with "eu" are pluralized with a "x" excepted "pneu", "bleu", "émeu"
        // Les mots finissant en "eu" prennent un "x" au pluriel sauf "pneu", "bleu", "émeu"
        ['/^(pneu|bleu|émeu)$/i', '\\1s'],
        ['/(eu)$/i', '\\1x'],
        // Words finishing with "al" are pluralized with a "aux" excepted
        // Les mots finissant en "al" se terminent en "aux" sauf
        ['/^(bal|carnaval|caracal|chacal|choral|corral|étal|festival|récital|val)$/i', '\\1s'],
        ['/al$/i', '\\1aux'],
        // Aspirail, bail, corail, émail, fermail, soupirail, travail, vantail et vitrail font leur pluriel en -aux
        ['/^(aspir|b|cor|ém|ferm|soupir|trav|vant|vitr)ail$/i', '\\1aux'],
        // Bijou, caillou, chou, genou, hibou, joujou et pou qui prennent un x au pluriel
        ['/^(bij|caill|ch|gen|hib|jouj|p)ou$/i', '\\1oux'],
        // Invariable words
        ['/^(cinquante|soixante|mille)$/i', '\\1'],
        // French titles
        ['/^(mon|ma)(sieur|dame|demoiselle|seigneur)$/', 'Phabel\\mes\\2s'],
        ['/^(Mon|Ma)(sieur|dame|demoiselle|seigneur)$/', 'Phabel\\Mes\\2s'],
    ];
    /**
     * A list of all rules for singularize.
     */
    const SINGULARIZE_REGEXP = [
        // First entry: regexp
        // Second entry: replacement
        // Aspirail, bail, corail, émail, fermail, soupirail, travail, vantail et vitrail font leur pluriel en -aux
        ['/((aspir|b|cor|ém|ferm|soupir|trav|vant|vitr))aux$/i', '\\1ail'],
        // Words finishing with "eau" are pluralized with a "x"
        // Les mots finissant par "eau" prennent tous un "x" au pluriel
        ['/(eau)x$/i', '\\1'],
        // Words finishing with "al" are pluralized with a "aux" expected
        // Les mots finissant en "al" se terminent en "aux" sauf
        ['/(amir|anim|arsen|boc|can|capit|capor|chev|crist|génér|hopit|hôpit|idé|journ|littor|loc|m|mét|minér|princip|radic|termin)aux$/i', '\\1al'],
        // Words finishing with "au" are pluralized with a "x" excepted "landau"
        // Les mots finissant par "au" prennent un "x" au pluriel sauf "landau"
        ['/(au)x$/i', '\\1'],
        // Words finishing with "eu" are pluralized with a "x" excepted "pneu", "bleu", "émeu"
        // Les mots finissant en "eu" prennent un "x" au pluriel sauf "pneu", "bleu", "émeu"
        ['/(eu)x$/i', '\\1'],
        //  Words finishing with "ou" are pluralized with a "s" excepted bijou, caillou, chou, genou, hibou, joujou, pou
        // Les mots finissant par "ou" prennent un "s" sauf bijou, caillou, chou, genou, hibou, joujou, pou
        ['/(bij|caill|ch|gen|hib|jouj|p)oux$/i', '\\1ou'],
        // French titles
        ['/^mes(dame|demoiselle)s$/', 'Phabel\\ma\\1'],
        ['/^Mes(dame|demoiselle)s$/', 'Phabel\\Ma\\1'],
        ['/^mes(sieur|seigneur)s$/', 'Phabel\\mon\\1'],
        ['/^Mes(sieur|seigneur)s$/', 'Phabel\\Mon\\1'],
        //Default rule
        ['/s$/i', ''],
    ];
    /**
     * A list of words which should not be inflected.
     * This list is only used by singularize.
     */
    const UNINFLECTED = '/^(abcès|accès|abus|albatros|anchois|anglais|autobus|bois|brebis|carquois|cas|chas|colis|concours|corps|cours|cyprès|décès|devis|discours|dos|embarras|engrais|entrelacs|excès|fils|fois|gâchis|gars|glas|héros|intrus|jars|jus|kermès|lacis|legs|lilas|marais|mars|matelas|mépris|mets|mois|mors|obus|os|palais|paradis|parcours|pardessus|pays|plusieurs|poids|pois|pouls|printemps|processus|progrès|puits|pus|rabais|radis|recors|recours|refus|relais|remords|remous|rictus|rhinocéros|repas|rubis|sas|secours|sens|souris|succès|talus|tapis|tas|taudis|temps|tiers|univers|velours|verglas|vernis|virus)$/i';
    /**
     * {@inheritdoc}
     */
    public function singularize($plural)
    {
        if (!\is_string($plural)) {
            if (!(\is_string($plural) || \is_object($plural) && \method_exists($plural, '__toString') || (\is_bool($plural) || \is_numeric($plural)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($plural) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plural) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $plural = (string) $plural;
            }
        }
        if ($this->isInflectedWord($plural)) {
            $phabelReturn = [$plural];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        foreach (self::SINGULARIZE_REGEXP as $rule) {
            list($regexp, $replace) = $rule;
            if (1 === \preg_match($regexp, $plural)) {
                $phabelReturn = [\preg_replace($regexp, $replace, $plural)];
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = [$plural];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function pluralize($singular)
    {
        if (!\is_string($singular)) {
            if (!(\is_string($singular) || \is_object($singular) && \method_exists($singular, '__toString') || (\is_bool($singular) || \is_numeric($singular)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($singular) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($singular) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $singular = (string) $singular;
            }
        }
        if ($this->isInflectedWord($singular)) {
            $phabelReturn = [$singular];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        foreach (self::PLURALIZE_REGEXP as $rule) {
            list($regexp, $replace) = $rule;
            if (1 === \preg_match($regexp, $singular)) {
                $phabelReturn = [\preg_replace($regexp, $replace, $singular)];
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = [$singular . 's'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function isInflectedWord($word)
    {
        if (!\is_string($word)) {
            if (!(\is_string($word) || \is_object($word) && \method_exists($word, '__toString') || (\is_bool($word) || \is_numeric($word)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($word) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($word) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $word = (string) $word;
            }
        }
        $phabelReturn = 1 === \preg_match(self::UNINFLECTED, $word);
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
