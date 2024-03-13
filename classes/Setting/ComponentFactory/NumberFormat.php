<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OpenFactory;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

class NumberFormat implements ComponentFactory
{

    private const NAME = 'number_format';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME);
        $number_decimals = $config->get('number_decimals') ?: 0;

        $builder = (new ComponentBuilder())
            ->set_label(__('Number Format', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array([
                        ''          => __('Default', 'codepress-admin-column'),
                        'formatted' => __('Formatted', 'codepress-admin-column'),
                    ]),
                    $value
                )
            )
            ->set_children(
                new Children(
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
                                $config->has('number_decimal_point') ? $config->get('number_decimal_point') : '.'
                            ),
                            StringComparisonSpecification::equal('formatted')
                        ),
                        new Component(
                            __('Thousands Separator', 'codepress-admin-columns'),
                            null,
                            OpenFactory::create_text(
                                'number_thousands_separator',
                                $config->has('number_thousands_separator') ? $config->get(
                                    'number_thousands_separator'
                                ) : ','
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
                )
            );

        $formatter = $this->get_formatter($config);

        if ( ! $formatter) {
            $builder->set_formatter($formatter);
        }

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function get_formatter(Config $config): ?Formatter
    {
        if ('formatted' === $config->get(self::NAME)) {
            return new Formatter\NumberFormat(
                $config->get('number_decimals') ?: 0,
                $config->get('number_decimal_point') ?: '.',
                $config->get('number_thousands_separator') ?: ','
            );
        }

        return null;
    }
}