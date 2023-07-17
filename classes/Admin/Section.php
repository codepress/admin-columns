<?php

namespace AC\Admin;

use AC\Renderable;

abstract class Section implements Renderable
{

    protected $slug;

    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

}