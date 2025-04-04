<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class ColumnId
{

    private string $id;

    public function __construct(string $id)
    {
        if ( ! self::is_valid_id($id)) {
            throw new InvalidArgumentException('Missing column identity.');
        }

        $this->id = $id;
    }

    public static function is_valid_id(string $id): bool
    {
        return '' !== $id;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    public function equals(ColumnId $id): bool
    {
        return $this->id === $id->get_id();
    }

    public function __toString(): string
    {
        return $this->id;
    }

}