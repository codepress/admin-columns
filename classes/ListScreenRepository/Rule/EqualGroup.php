<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;

class EqualGroup implements Rule
{

    private string $group;

    public function __construct(string $group)
    {
        $this->group = $group;
    }

    public function match(array $args): bool
    {
        if ( ! isset($args[self::GROUP])) {
            return false;
        }

        return $args[self::GROUP] === $this->group;
    }

}