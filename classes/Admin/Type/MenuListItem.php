<?php

declare(strict_types=1);

namespace AC\Admin\Type;

class MenuListItem
{

    private $key;

    private $label;

    private $group;

    public function __construct(string $key, string $label, string $group)
    {
        $this->key = $key;
        $this->label = $label;
        $this->group = $group;
    }

    public function get_key(): string
    {
        return $this->key;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_group(): string
    {
        return $this->group;
    }

}