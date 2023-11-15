<?php

declare(strict_types=1);

namespace AC\Setting;

trait SettingTrait
{

    protected $name;

    protected $label = '';

    protected $description = '';

    protected $input;

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

}