<?php

declare(strict_types=1);

namespace AC\Type;

class ValueSeparator
{

    private $separator;

    public function __construct(string $separator = null)
    {
        if (null === $separator) {
            $separator = ', ';
        }

        $this->separator = $separator;
    }

    public function __toString(): string
    {
        return $this->separator;
    }

}