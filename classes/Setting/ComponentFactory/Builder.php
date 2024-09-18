<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\FormatterCollection;

abstract class Builder extends Base
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = new ComponentBuilder();

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

        $builder->set_formatters(
            $this->get_formatters($config, new FormatterCollection())
        );

        $children = $this->get_children($config);

        if ($children !== null) {
            $builder->set_children($children);
        }

        $builder->set_attributes(
            $this->get_attributes($config, new AttributeCollection())
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

    // TODO $formatters do not need to be returned
    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $children = $this->get_children($config);

        if ($children) {
            $input = $this->get_input($config);

            $formatters = $this->get_formatters_recursive(
                $children->get_iterator(),
                $formatters,
                $input ? (string)$input->get_value() : null
            );
        }

        return $formatters;
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