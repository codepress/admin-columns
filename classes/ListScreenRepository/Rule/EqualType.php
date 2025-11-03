<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;

class EqualType implements Rule
{

    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function match(array $args)
    {
        if ( ! isset($args[self::TYPE])) {
            return false;
        }

        return $args[self::TYPE] === $this->type;
    }

}