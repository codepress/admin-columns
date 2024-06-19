<?php

namespace AC\Admin\Type;

class MenuItem
{

    private $slug;

    private $url;

    private $label;

    private $class;

    private $target;

    public function __construct(string $slug, string $url, string $label, string $class = '', string $target = '')
    {
        $this->slug = $slug;
        $this->url = $url;
        $this->label = $label;
        $this->class = $class;
        $this->target = $target;
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

}