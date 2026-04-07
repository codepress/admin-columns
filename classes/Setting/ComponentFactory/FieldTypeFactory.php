<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\AttributeFactory;
use AC\Setting\Children;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\Type\Option;

class FieldTypeFactory extends BaseComponentFactory
{

    private array $field_types;

    private array $formatter_configs;

    private array $children_configs;

    public function __construct(array $field_types, array $formatter_configs = [], array $children_configs = [])
    {
        $this->field_types = $field_types;
        $this->formatter_configs = $formatter_configs;
        $this->children_configs = $children_configs;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Field Type', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This will determine how the value will be displayed.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'field_type',
            $this->get_field_type_options(),
            $config->get('field_type', ''),
            __('Select the field type', 'codepress-admin-columns'),
            false,
            new AC\Setting\AttributeCollection([
                AttributeFactory::create_refresh(),
            ])
        );
    }

    protected function get_field_type_options(): OptionCollection
    {
        $collection = new OptionCollection();
        $collection->add(
            new Option(__('Default', 'codepress-admin-columns'), '')
        );

        $groups = [
            'basic'      => __('Basic', 'codepress-admin-columns'),
            'relational' => __('Relational', 'codepress-admin-columns'),
            'choice'     => __('Choice', 'codepress-admin-columns'),
            'multiple'   => __('Multiple', 'codepress-admin-columns'),
            'custom'     => __('Custom', 'codepress-admin-columns'),
        ];

        foreach ($this->field_types as $group => $options) {
            foreach ($options as $value => $label) {
                $collection->add(
                    new Option(
                        (string)$label,
                        (string)$value,
                        $groups[$group] ?? $group
                    )
                );
            }
        }

        return $collection;
    }

    protected function add_formatters(Setting\Config $config, AC\FormatterCollection $formatters): void
    {
        $field_type = $config->get('field_type', '');
        $configs = $this->formatter_configs[$field_type] ?? [];

        foreach ($configs as $formatter_config) {
            $formatter_config($config, $formatters);
        }
    }

    protected function get_children(Setting\Config $config): ?Children
    {
        $components = [];
        $field_type = $config->get('field_type', '');

        foreach ($this->children_configs as $child_config) {
            /**
             * @var BaseComponentFactory $component_factory
             * @var Specification        $specification
             */
            [$component_factory, $specification] = $child_config;

            if ($specification->is_satisfied_by($field_type)) {
                $components[] = $component_factory->create($config, $specification);
            }
        }

        return new Children(new AC\Setting\ComponentCollection($components));
    }

}