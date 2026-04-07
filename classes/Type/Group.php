<?php

declare(strict_types=1);

namespace AC\Type;

final class Group
{

    private string $slug;

    private string $label;

    private int $priority;

    private ?string $icon;

    public function __construct(string $slug, string $label, int $priority = 10, ?string $icon = null)
    {
        $this->slug = $slug;
        $this->label = $label;
        $this->priority = $priority;
        $this->icon = $icon;
    }

    public function get_slug(): string
    {
        return $this->slug;
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

    /**
     * Should return an absolute location to a square shaped image
     */
    public function get_icon(): ?string
    {
        return $this->icon;
    }

}