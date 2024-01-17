<?php

namespace AC\Settings;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\Input;
use AC\Setting\Setting;

abstract class Column implements Setting
{

    protected $name;

    protected $label;

    protected $description = '';

    protected $input;

    protected $conditions;

    public function __construct(
        string $name,
        string $label,
        string $description = null,
        Input $input = null,
        Specification $conditions = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->conditions = $conditions ?? new NullSpecification();
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function has_description(): bool
    {
        return null !== $this->description;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function has_input(): bool
    {
        return null !== $this->input;
    }

    public function get_input(): Input
    {
        return $this->input;
    }

    public function get_conditions(): Specification
    {
        return $this->conditions;
    }

}