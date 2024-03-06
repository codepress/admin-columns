<?php

declare(strict_types=1);

namespace AC\Settings;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Control\Input;
use AC\Setting\Formatter;
use BadMethodCallException;

final class Setting
{

    private $type;

    private $label;

    private $description;

    private $attributes;

    private $input;

    private $conditions;

    private $formatter;

    private $children;

    public function __construct(
        string $type,
        string $label,
        string $description = '',
        Input $input = null,
        Specification $conditions = null,
        Formatter $formatter = null,
        ComponentCollection $children = null,
        AttributeCollection $attributes = null
    ) {
        if ($conditions === null) {
            $conditions = new NullSpecification();
        }

        if ($formatter === null) {
            $formatter = new Formatter\NullFormatter();
        }

        if ($children === null) {
            $children = new ComponentCollection();
        }

        if ($attributes === null) {
            $attributes = new AttributeCollection();
        }

        $this->type = $type;
        $this->label = trim($label);
        $this->description = trim($description);
        $this->input = $input;
        $this->conditions = $conditions;
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

    public function has_description(): bool
    {
        return ! empty($this->description);
    }

    public function get_description(): string
    {
        if ( ! $this->has_description()) {
            throw new BadMethodCallException();
        }

        return $this->description;
    }

    public function get_attributes(): AttributeCollection
    {
        return $this->attributes;
    }

    public function has_input(): bool
    {
        return $this->input !== null;
    }

    public function get_input(): Input
    {
        if ( ! $this->has_input()) {
            throw new BadMethodCallException();
        }

        return $this->input;
    }

    public function get_conditions(): Specification
    {
        return $this->conditions;
    }

    public function get_formatter(): Formatter
    {
        return $this->formatter;
    }

    public function get_children(): ComponentCollection
    {
        return $this->children;
    }

}