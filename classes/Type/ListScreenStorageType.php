<?php

declare(strict_types=1);

namespace AC\Type;

class ListScreenStorageType
{

    public const TEMPLATE = 'template';

    private string $type;

    public function __construct(string $type = '')
    {
        $this->type = $type;
    }

    public static function create_template(): self
    {
        return new self(self::TEMPLATE);
    }

    public function __toString(): string
    {
        return $this->type;
    }
}