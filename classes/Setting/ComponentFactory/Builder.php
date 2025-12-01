<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\FormatterCollection;

abstract class Builder implements ComponentFactory
{

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = new ComponentBuilder();
        $formatters = new FormatterCollection();
        $attributes = new AttributeCollection();

        if ($conditions !== null) {
            $builder->set_conditions($conditions);
        }

        $label = $this->get_label($config);

        if ($label !== null) {
            $builder->set_label($label);
        }

        $description = $this->get_description($config);

        if ($description !== null) {
            $builder->set_description($description);
        }

        $input = $this->get_input($config);

        if ($input !== null) {
            $builder->set_input($input);
        }

        $this->add_formatters($config, $formatters);

        $children = $this->get_children($config);

        if ($children !== null) {
            $builder->set_children($children);

            $this->add_component_formatters(
                $formatters,
                $children->get_iterator(),
                $input
                    ? (string)$input->get_value()
                    : null
            );
        }

        $this->add_final_formatters($config, $formatters);

        $builder->set_formatters($formatters);

        $builder->set_attributes(
            $this->get_attributes($config, $attributes)
        );

        $type = $this->get_type($config);

        if ($type !== null) {
            $builder->set_type($type);
        }

        return $builder->build();
    }

    protected function get_label(Config $config): ?string
    {
        return null;
    }

    protected function get_description(Config $config): ?string
    {
        return null;
    }

    protected function get_input(Config $config): ?Input
    {
        return null;
    }

    protected function add_component_formatters(
        FormatterCollection $formatters,
        ComponentCollection $components,
        ?string $condition = null
    ): void {
        foreach ($components as $component) {
            if ($component->get_conditions()->is_satisfied_by((string)$condition)) {
                foreach ($component->get_formatters() as $formatter) {
                    $formatters->add($formatter);
                }
            }
        }
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
    }

    protected function add_final_formatters(Config $config, FormatterCollection $formatters): void
    {
    }

    protected function get_children(Config $config): ?Children
    {
        return null;
    }

    protected function get_attributes(Config $config, AttributeCollection $attributes): AttributeCollection
    {
        return $attributes;
    }

    protected function get_type(Config $config): ?string
    {
        return null;
    }

}