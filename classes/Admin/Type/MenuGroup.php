<?php

declare(strict_types=1);

namespace AC\Admin\Type;

class MenuGroup
{

    private $name;

    private $label;

    private $priority;

    private $icon;

    public function __construct(string $name, string $label, int $priority = 20, string $icon = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->priority = $priority;
        $this->icon = $icon;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_priority(): int
    {
        return $this->priority;
    }

    public function has_icon(): bool
    {
        return null !== $this->icon;
    }

    public function get_icon(): string
    {
        return $this->icon;
    }

}