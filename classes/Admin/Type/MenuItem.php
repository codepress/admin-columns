<?php

declare(strict_types=1);

namespace AC\Admin\Type;

class MenuItem
{
    public const GROUP_PRIMARY = '';

    public const GROUP_MORE = 'more';

    private string $slug;

    private string $url;

    private string $label;

    private string $class;

    private string $target;

    private int $position;

    private string $group;

    public function __construct(
        string $slug,
        string $url,
        string $label,
        string $class = '',
        string $target = '',
        int $position = 10
    ) {
        $this->slug = $slug;
        $this->url = $url;
        $this->label = $label;
        $this->class = $class;
        $this->target = $target;
        $this->position = $position;
        $this->group = self::GROUP_PRIMARY;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_class(): string
    {
        return $this->class;
    }

    public function get_target(): string
    {
        return $this->target;
    }

    public function get_position(): int
    {
        return $this->position;
    }

    public function get_group(): string
    {
        return $this->group;
    }

    public function with_group(string $group): self
    {
        $clone = clone $this;
        $clone->group = $group;

        return $clone;
    }

    public function with_position(int $position): self
    {
        $clone = clone $this;
        $clone->position = $position;

        return $clone;
    }

}
