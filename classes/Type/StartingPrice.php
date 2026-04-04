<?php

declare(strict_types=1);

namespace AC\Type;

class StartingPrice
{

    private const EURO = '€79';
    private const DOLLAR = '$79';

    public static function get(): string
    {
        return self::is_european_locale() ? self::EURO : self::DOLLAR;
    }

    private static function is_european_locale(): bool
    {
        $locale = determine_locale();

        $european_locales = [
            'de_DE', 'de_DE_formal', 'de_AT', 'de_CH', 'de_CH_informal', 'de_LU',
            'fr_FR', 'fr_BE', 'fr_CA',
            'nl_NL', 'nl_NL_formal', 'nl_BE',
            'es_ES', 'es_AR', 'es_CL', 'es_CO', 'es_CR', 'es_DO', 'es_EC', 'es_GT', 'es_HN', 'es_MX', 'es_PE', 'es_PR', 'es_UY', 'es_VE',
            'it_IT',
            'pt_PT', 'pt_BR', 'pt_AO',
            'da_DK',
            'sv_SE',
            'nb_NO', 'nn_NO',
            'fi',
            'el',
            'pl_PL',
            'cs_CZ',
            'sk_SK',
            'hu_HU',
            'ro_RO',
            'bg_BG',
            'hr',
            'sl_SI',
            'et',
            'lv',
            'lt_LT',
            'ga',
            'mt_MT',
            'cy',
            'eu',
            'gl_ES',
            'ca',
        ];

        return in_array($locale, $european_locales, true);
    }

}
