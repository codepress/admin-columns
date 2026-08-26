<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\FormatterCollection;
use AC\Setting;
use AC\Setting\Component;
use AC\Setting\Config;
use AC\Setting\Type\Attribute;

class FieldTypeFactoryBuilder
{
    private array $field_types = [];

    private array $formatter_configs = [];

    private array $final_formatter_configs = [];

    private array $children_resolvers = [];

    private array $attributes = [];

    public function add_option(string $type, string $label, string $group): self
    {
        $this->field_types[$group][$type] = $label;

        natcasesort($this->field_types[$group]);

        return $this;
    }

    /**
     * @param callable(Config, FormatterCollection):void $formatter_factory
     */
    public function add_formatter(string $type, callable $formatter_factory): self
    {
        $this->formatter_configs[$type][] = $formatter_factory;

        return $this;
    }

    /**
     * @param callable(Config, FormatterCollection):void $formatter_factory
     */
    public function add_final_formatter(string $type, callable $formatter_factory): self
    {
        $this->final_formatter_configs[$type][] = $formatter_factory;

        return $this;
    }

    public function add_attribute(string $type, Attribute $attribute): self
    {
        $this->attributes[$type][] = $attribute;

        return $this;
    }

    public function add_child_component(Setting\ComponentFactory $component_factory, Specification $specification): self
    {
        return $this->add_child_component_resolver(
            static function (Config $config) use ($component_factory, $specification): ?Component {
                return $specification->is_satisfied_by((string)$config->get('field_type', ''))
                    ? $component_factory->create($config, $specification)
                    : null;
            }
        );
    }

    /**
     * Register a child component that depends on more than the selected field type. The resolver
     * returns null when the component does not apply to the given config.
     *
     * @param callable(Config):?Component $resolver
     */
    public function add_child_component_resolver(callable $resolver): self
    {
        $this->children_resolvers[] = $resolver;

        return $this;
    }

    public function build(): FieldTypeFactory
    {
        return new FieldTypeFactory(
            $this->field_types,
            $this->formatter_configs,
            $this->children_resolvers,
            $this->final_formatter_configs,
            $this->attributes
        );
    }

}
