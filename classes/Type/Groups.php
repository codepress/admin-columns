<?php

namespace AC\Type;

use AC\Collection;

final class Groups extends Collection
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

    public function current(): Group
    {
        return current($this->data);
    }

}