<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;
use AC\Setting\AttributeCollection;
use BadMethodCallException;

class Component extends AC\Setting\Component
{

    private $type;

    private $label;

    private $description;

    private $control;

    private $attributes;

    public function ____construct(
        string $type,
        string $label,
        string $description = null,
        Control $control = null,
        AttributeCollection $attributes = null
    ) {
        if (null === $attributes) {
            $attributes = new AttributeCollection();
        }

        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
        $this->control = $control;
        $this->attributes = $attributes;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_attributes(): AttributeCollection
    {
        return clone $this->attributes;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function has_description(): bool
    {
        return $this->has_description() !== null;
    }

    public function get_description(): string
    {
        if ( ! $this->has_description()) {
            throw new BadMethodCallException();
        }

        return $this->description;
    }

    public function has_control(): bool
    {
        return $this->control instanceof Control;
    }

}