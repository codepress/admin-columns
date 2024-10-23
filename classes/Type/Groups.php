<?php

namespace AC\Type;

use AC\Iterator;

final class Groups extends Iterator
{

    public function __construct(array $groups = [])
    {
        array_map([$this, 'add'], $groups);
    }

    public function add(Group $group): self
    {
        $this->data[] = $group;

        return $this;
    }

}