<?php

declare(strict_types=1);

namespace AC\Type;

final class ListScreenIdGenerator extends KeyGenerator
{

    public function generate(): ListScreenId
    {
        return new ListScreenId($this->generate_raw());
    }

}