<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;

class SerializedDisplay extends Builder
{

    private SerializedArrayKeys $array_keys;

    public function __construct(SerializedArrayKeys $array_keys)
    {
        $this->array_keys = $array_keys;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Value Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return Input\OptionFactory::create_select(
            'serialized_display',
            OptionCollection::from_array([
                ''          => __('Comma separated', 'codepress-admin-columns'),
                'formatted' => __('Formatted', 'codepress-admin-columns'),
            ]),
            $config->get('serialized_display', ''),
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(new ComponentCollection([
            $this->array_keys->create($config, StringComparisonSpecification::equal('formatted')),
        ]));
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        switch ($config->get('serialized_display')) {
            case 'formatted':
                $keys = array_filter(array_map('trim', explode('.', $config->get('array_keys', ''))));

                $formatters->add(new AC\Value\Formatter\FormattedJson($keys));
                break;
            default:
                $formatters->add(new AC\Value\Formatter\ImplodeRecursive());
                break;
        }

        parent::add_formatters($config, $formatters);
    }

}