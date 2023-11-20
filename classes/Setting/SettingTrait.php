<?php

declare(strict_types=1);

namespace AC\Setting;

trait SettingTrait
{

    private $name;

    private $label = '';

    private $description = '';

    private $input;

    /**
     * @var ConditionCollection
     */
    private $conditions;

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_input(): Input
    {
        return $this->input;
    }

    public function has_conditions(): bool
    {
        return $this->conditions->count() > 0;
    }

    public function get_conditions(): ConditionCollection
    {
        return $this->conditions;
    }

}