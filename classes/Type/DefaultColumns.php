<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

class DefaultColumns extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(DefaultColumn $component): self
    {
        $this->data[] = $component;

        return $this;
    }

    public function current(): DefaultColumn
    {
        return current($this->data);
    }

}