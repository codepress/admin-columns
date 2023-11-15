<?php

declare(strict_types=1);

namespace AC\Setting;

class Value
{

    private $type;

    public function __construct(string $type = null)
    {
        if ($type === null) {
            $type = ValueTypes::STRING;
        }

        $this->type = $type;
    }

    public function get_type(): string
    {
        return $this->type;
    }

}