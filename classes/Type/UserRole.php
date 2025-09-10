<?php

declare(strict_types=1);

namespace AC\Type;

class UserRole
{

    private string $name;

    private string $label;

    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_translate_label(): string
    {
        return translate_user_role($this->label);
    }
}