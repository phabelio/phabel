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
    private const PLURALIZE_REGEXP = array(0 => array(0 => '/(s|x|z)$/i', 1 => '\\1'), 1 => array(0 => '/(eau)$/i', 1 => '\\1x'), 2 => array(0 => '/^(landau)$/i', 1 => '\\1s'), 3 => array(0 => '/(au)$/i', 1 => '\\1x'), 4 => array(0 => '/^(pneu|bleu|émeu)$/i', 1 => '\\1s'), 5 => array(0 => '/(eu)$/i', 1 => '\\1x'), 6 => array(0 => '/^(bal|carnaval|caracal|chacal|choral|corral|étal|festival|récital|val)$/i', 1 => '\\1s'), 7 => array(0 => '/al$/i', 1 => '\\1aux'), 8 => array(0 => '/^(aspir|b|cor|ém|ferm|soupir|trav|vant|vitr)ail$/i', 1 => '\\1aux'), 9 => array(0 => '/^(bij|caill|ch|gen|hib|jouj|p)ou$/i', 1 => '\\1oux'), 10 => array(0 => '/^(cinquante|soixante|mille)$/i', 1 => '\\1'), 11 => array(0 => '/^(mon|ma)(sieur|dame|demoiselle|seigneur)$/', 1 => 'PhabelVendor\\mes\\2s'), 12 => array(0 => '/^(Mon|Ma)(sieur|dame|demoiselle|seigneur)$/', 1 => 'PhabelVendor\\Mes\\2s'));
    /**
     * A list of all rules for singularize.
     */
    private const SINGULARIZE_REGEXP = array(0 => array(0 => '/((aspir|b|cor|ém|ferm|soupir|trav|vant|vitr))aux$/i', 1 => '\\1ail'), 1 => array(0 => '/(eau)x$/i', 1 => '\\1'), 2 => array(0 => '/(amir|anim|arsen|boc|can|capit|capor|chev|crist|génér|hopit|hôpit|idé|journ|littor|loc|m|mét|minér|princip|radic|termin)aux$/i', 1 => '\\1al'), 3 => array(0 => '/(au)x$/i', 1 => '\\1'), 4 => array(0 => '/(eu)x$/i', 1 => '\\1'), 5 => array(0 => '/(bij|caill|ch|gen|hib|jouj|p)oux$/i', 1 => '\\1ou'), 6 => array(0 => '/^mes(dame|demoiselle)s$/', 1 => 'PhabelVendor\\ma\\1'), 7 => array(0 => '/^Mes(dame|demoiselle)s$/', 1 => 'PhabelVendor\\Ma\\1'), 8 => array(0 => '/^mes(sieur|seigneur)s$/', 1 => 'PhabelVendor\\mon\\1'), 9 => array(0 => '/^Mes(sieur|seigneur)s$/', 1 => 'PhabelVendor\\Mon\\1'), 10 => array(0 => '/s$/i', 1 => ''));
    /**
     * A list of words which should not be inflected.
     * This list is only used by singularize.
     */
    private const UNINFLECTED = '/^(abcès|accès|abus|albatros|anchois|anglais|autobus|bois|brebis|carquois|cas|chas|colis|concours|corps|cours|cyprès|décès|devis|discours|dos|embarras|engrais|entrelacs|excès|fils|fois|gâchis|gars|glas|héros|intrus|jars|jus|kermès|lacis|legs|lilas|marais|mars|matelas|mépris|mets|mois|mors|obus|os|palais|paradis|parcours|pardessus|pays|plusieurs|poids|pois|pouls|printemps|processus|progrès|puits|pus|rabais|radis|recors|recours|refus|relais|remords|remous|rictus|rhinocéros|repas|rubis|sas|secours|sens|souris|succès|talus|tapis|tas|taudis|temps|tiers|univers|velours|verglas|vernis|virus)$/i';
    /**
     * {@inheritdoc}
     */
    public function singularize(string $plural) : array
    {
        if ($this->isInflectedWord($plural)) {
            return [$plural];
        }
        foreach (self::SINGULARIZE_REGEXP as $rule) {
            [$regexp, $replace] = $rule;
            if (1 === \preg_match($regexp, $plural)) {
                return [\preg_replace($regexp, $replace, $plural)];
            }
        }
        return [$plural];
    }
    /**
     * {@inheritdoc}
     */
    public function pluralize(string $singular) : array
    {
        if ($this->isInflectedWord($singular)) {
            return [$singular];
        }
        foreach (self::PLURALIZE_REGEXP as $rule) {
            [$regexp, $replace] = $rule;
            if (1 === \preg_match($regexp, $singular)) {
                return [\preg_replace($regexp, $replace, $singular)];
            }
        }
        return [$singular . 's'];
    }
    /**
     *
     */
    private function isInflectedWord(string $word) : bool
    {
        return 1 === \preg_match(self::UNINFLECTED, $word);
    }
}
