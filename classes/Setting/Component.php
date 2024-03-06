<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Control\AttributeCollection;
use InvalidArgumentException;

class Component
{

    private $type;

    private $label;

    private $description;

    private $control;

    private $formatter;

    private $children;

    private $attributes;

    public function __construct(
        string $type,
        string $label,
        string $description,
        Control $control = null,
        Formatter $formatter = null,
        Children $children = null,
        AttributeCollection $attributes = null
    ) {
        if ($attributes === null) {
            $attributes = new AttributeCollection();
        }

        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
        $this->control = $control;
        $this->formatter = $formatter;
        $this->children = $children;
        $this->attributes = $attributes;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_description(): string
    {
        if ( ! $this->has_description()) {
            throw new InvalidArgumentException();
        }
    }

    public function has_description(): bool
    {
        return $this->description !== null;
    }

    public function has_control(): bool
    {
        return $this->control !== null;
    }

    public function get_control(): Control
    {
        if ( ! $this->has_control()) {
            throw new InvalidArgumentException();
        }

        return $this->control;
    }

    public function has_formatter(): bool
    {
        return $this->formatter !== null;
    }

    public function get_formatter(): Formatter
    {
        if ( ! $this->has_formatter()) {
            return new Formatter\NullFormatter();
        }

        return $this->formatter;
    }

    public function has_children(): bool
    {
        return $this->children !== null;
    }

    public function get_children(): Children
    {
        if ( ! $this->has_children()) {
            throw new InvalidArgumentException();
        }

        return $this->children;
    }

    public function get_attributes(): AttributeCollection
    {
        return $this->attributes;
    }

}