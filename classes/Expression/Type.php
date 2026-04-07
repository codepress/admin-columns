<?php

declare(strict_types=1);

namespace AC\Expression;

use DomainException;

final class Type
{

    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;

        $this->validate();
    }

    private function validate(): void
    {
        $valid = [
            Types::STRING,
            Types::INTEGER,
            Types::FLOAT,
            Types::DATE,
        ];

        if ( ! in_array($this->type, $valid, true)) {
            throw new DomainException('Invalid type.');
        }
    }

    public static function string(): self
    {
        return new self(Types::STRING);
    }

    public static function integer(): self
    {
        return new self(Types::INTEGER);
    }

    public static function float(): self
    {
        return new self(Types::FLOAT);
    }

    public static function date(): self
    {
        return new self(Types::DATE);
    }

    public function __toString(): string
    {
        return $this->type;
    }

}