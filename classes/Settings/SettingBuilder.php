<?php

declare(strict_types=1);

namespace AC\Settings;

use AC\Expression\Specification;
use AC\Setting\Control\AttributeCollection;
use AC\Setting\Control\Input;
use AC\Setting\Control\Type\Attribute;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;

final class SettingBuilder
{

    private $type;

    private $label;

    private $attributes;

    private $input;

    private $conditions;

    private $formatter;

    private $children;

    public function __construct()
    {
        $this->attributes = new AttributeCollection();
    }

    public function set_type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function set_label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    // TODO David maybe just add description to the stack?
    public function set_description(string $description): self
    {
        $this->attributes->add(new Attribute('description', $description));

        return $this;
    }

    public function add_attribute(Attribute $attribute): self
    {
        $this->attributes->add($attribute);

        return $this;
    }

    public function set_input(Input $input): self
    {
        $this->input = $input;

        return $this;
    }

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

    public function set_children(ComponentCollection $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function build(): Setting
    {
        return new Setting(
            $this->type,
            $this->label,
            $this->attributes,
            $this->input,
            $this->conditions,
            $this->formatter,
            $this->children
        );
    }

}