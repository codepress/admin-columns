<?php

declare(strict_types=1);

namespace AC\Setting;

class Children
{

    private ComponentCollection $components;

    private bool $parent;

    public function __construct(ComponentCollection $components, bool $parent = false)
    {
        $this->components = $components;
        $this->parent = $parent;
    }

    public function get_iterator(): ComponentCollection
    {
        return $this->components;
    }

    public function is_parent(): bool
    {
        return $this->parent === true;
    }

}