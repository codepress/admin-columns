<?php

declare(strict_types=1);

namespace AC\Admin\Type;

class MenuGroup
{

    private string $name;

    private string $label;

    /**
     * 1-10 is highet prio and reserved for core menu items.
     * 10-20 is medium priority and recommended for integration addons.
     * 20-30 is low priority and recommended for less important menu items. (e.g. taxonomy list tables)
     * 30-40 is lowerst priority and mostly used for hidden list tables (e.g. post types that are not visible in the admin menu)
     */
    private int $priority;

    private ?string $icon;

    public function __construct(string $name, string $label, int $priority = 40, ?string $icon = null)
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