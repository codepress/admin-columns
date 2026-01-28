<?php

declare(strict_types=1);

namespace AC\Admin\Type;

class MenuListItem
{

    private string $key;

    private string $label;

    private MenuGroup $group;

    public function __construct(string $key, string $label, MenuGroup $group)
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

    public function get_group(): MenuGroup
    {
        return $this->group;
    }

}