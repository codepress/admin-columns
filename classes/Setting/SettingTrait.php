<?php

declare(strict_types=1);

namespace AC\Setting;

trait SettingTrait
{

    private $name;

    private $label = '';

    private $description = '';

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

}