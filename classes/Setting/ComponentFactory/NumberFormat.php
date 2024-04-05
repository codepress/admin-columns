<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OpenFactory;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class NumberFormat extends Builder
{

    private const NAME = 'number_format';

    protected function get_label(Config $config): ?string
    {
        return __('Number Format', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array([
                ''          => __('Default', 'codepress-admin-column'),
                'formatted' => __('Formatted', 'codepress-admin-column'),
            ]),
            $config->get(self::NAME, '')
        );
    }

    protected function get_children(Config $config): ?Children
    {
        $number_decimals = (int)$config->get('number_decimals', 0);

        return new Children(
            new ComponentCollection([
                new Component(
                    __('Decimals', 'codepress-admin-columns'),
                    null,
                    Number::create_single_step('number_decimals', 0, 20, $number_decimals),
                    StringComparisonSpecification::equal('formatted')
                ),
                new Component(
                    __('Decimal Point', 'codepress-admin-columns'),
                    null,
                    OpenFactory::create_text(
                        'number_decimal_point',
                        $config->get('number_decimal_point', '.')
                    ),
                    StringComparisonSpecification::equal('formatted')
                ),
                new Component(
                    __('Thousands Separator', 'codepress-admin-columns'),
                    null,
                    OpenFactory::create_text(
                        'number_thousands_separator',
                        $config->get('number_thousands_separator', ',')
                    ),
                    StringComparisonSpecification::equal('formatted')
                ),
                new Component(
                    __('Preview', 'codepress-admin-columns'),
                    null,
                    new Custom('number_preview', null, [
                        'keys' => ['number_decimals', 'number_decimal_point', 'number_thousands_separator'],
                    ]),
                    StringComparisonSpecification::equal('formatted')
                ),
            ])
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        if ($config->get(self::NAME, '') === 'formatted') {
            $formatters->add(
                new Formatter\NumberFormat(
                    (int)$config->get('number_decimals', 0),
                    $config->get('number_decimal_point', '.'),
                    $config->get('number_thousands_separator', ',')
                )
            );
        }

        return parent::get_formatters($config, $formatters);
    }

}