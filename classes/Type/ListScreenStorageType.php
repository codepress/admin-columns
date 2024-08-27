<?php

declare(strict_types=1);

namespace AC\Type;

class ListScreenStorageType
{

    private $type;

    public function __construct(string $type = '')
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}