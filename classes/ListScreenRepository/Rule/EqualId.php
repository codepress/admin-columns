<?php

namespace AC\ListScreenRepository\Rule;

use AC\ListScreenRepository\Rule;
use AC\Type\ListScreenId;

class EqualId implements Rule
{

    private ListScreenId $id;

    public function __construct(ListScreenId $id)
    {
        $this->id = $id;
    }

    public function match(array $args): bool
    {
        if ( ! isset($args[self::ID])) {
            return false;
        }

        return $this->id->equals($args[self::ID]);
    }

}