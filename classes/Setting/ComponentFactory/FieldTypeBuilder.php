<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting;

class FieldTypeBuilder
{

    private array $field_types = [];

    private array $formatter_configs = [];

    private array $children_configs = [];
    
    public function add_option(string $type, string $label, string $group): self
    {
        $this->field_types[$group][$type] = $label;

        natcasesort($this->field_types[$group]);

        return $this;
    }

    /**
     * @param callable(Setting\Config, Setting\FormatterCollection):void $formatter_factory
     */
    public function add_formatter(string $type, callable $formatter_factory): self
    {
        $this->formatter_configs[$type][] = $formatter_factory;

        return $this;
    }

    public function add_child_component(Setting\ComponentFactory $component_factory, Specification $specification): self
    {
        $this->children_configs[] = [$component_factory, $specification];

        return $this;
    }

    public function build(): FieldTypeFactory
    {
        return new FieldTypeFactory(
            $this->field_types,
            $this->formatter_configs,
            $this->children_configs
        );
    }

}