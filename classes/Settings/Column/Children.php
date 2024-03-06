<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\ComponentCollection;

final class Children
{

    private $components;

    private $parent;

    public function __construct(ComponentCollection $components, bool $parent = true)
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
        return $this->parent;
    }

}