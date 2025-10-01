<?php

namespace AC\Admin\Type;

class MenuItem
{

    private string $slug;

    private string $url;

    private string $label;

    private string $class;

    private string $target;

    private int $position;

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

}