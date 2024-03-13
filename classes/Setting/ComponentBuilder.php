<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Setting\Control\Input;

final class ComponentBuilder
{

    private $label;

    private $description;

    private $input;

    private $conditions;

    private $formatter;

    private $children;

    private $attributes;

    private $type;

    public function set_label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function set_description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function set_input(Input $input): self
    {
        $this->input = $input;

        return $this;
    }

    // TODO David maybe allow null here OR force a NULL condition?
    public function set_conditions(Specification $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function set_formatter(Formatter $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function set_children(Children $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function set_attributes(AttributeCollection $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function set_type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function build(): Component
    {
        return new Component(
            $this->label,
            $this->description,
            $this->input,
            $this->conditions,
            $this->formatter,
            $this->children,
            $this->attributes,
            $this->type
        );
    }

}