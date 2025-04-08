<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class TableId
{

    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;

        if ( ! self::validate($id)) {
            throw new InvalidArgumentException('List key can not be empty.');
        }
    }

    public static function validate(string $id): bool
    {
        return '' !== $id;
    }

    public function equals(TableId $id): bool
    {
        return $this->id === (string)$id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

}