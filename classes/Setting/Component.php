<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\Control\Input;
use InvalidArgumentException;

class Component
{

    private ?string $label;

    private ?string $description;

    private ?Input $input;

    private ?FormatterCollection $formatters;

    private ?Children $children;

    private $conditions;

    private ?AttributeCollection $attributes;

    private ?string $type;

    public function __construct(
        string $label = null,
        string $description = null,
        Input $input = null,
        Specification $conditions = null,
        FormatterCollection $formatters = null,
        Children $children = null,
        AttributeCollection $attributes = null,
        string $type = null
    ) {
        if ($conditions === null) {
            $conditions = new NullSpecification();
        }

        if ($formatters === null) {
            $formatters = new FormatterCollection();
        }

        if ($attributes === null) {
            $attributes = new AttributeCollection();
        }

        if ($type === null) {
            $type = 'default';
        }

        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->formatters = $formatters;
        $this->children = $children;
        $this->conditions = $conditions;
        $this->attributes = $attributes;
        $this->type = $type;
    }

    public function has_label(): bool
    {
        return $this->label !== null;
    }

    public function get_label(): string
    {
        if ( ! $this->has_label()) {
            throw new InvalidArgumentException();
        }

        return $this->label;
    }

    public function has_description(): bool
    {
        return $this->description !== null;
    }

    public function get_description(): string
    {
        if ( ! $this->has_description()) {
            throw new InvalidArgumentException();
        }

        return $this->description;
    }

    public function has_input(): bool
    {
        return $this->input !== null;
    }

    public function get_input(): Input
    {
        if ( ! $this->has_input()) {
            throw new InvalidArgumentException();
        }

        return $this->input;
    }

    public function get_formatters(): FormatterCollection
    {
        return $this->formatters;
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

    public function get_conditions(): Specification
    {
        return $this->conditions;
    }

    public function get_attributes(): AttributeCollection
    {
        return $this->attributes;
    }

    public function get_type(): string
    {
        return $this->type;
    }

}