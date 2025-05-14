<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

class ListScreenType
{

    private const TEMPLATE = 'template';

    private string $type;

    public function __construct(string $type = '')
    {
        $this->type = $type;

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! in_array($this->type, [self::TEMPLATE, ''], true)) {
            throw new InvalidArgumentException('Invalid list screen type');
        }
    }

    public static function create_default(): self
    {
        // TODO add default name
        return new self();
    }

    public static function create_template(): self
    {
        return new self(self::TEMPLATE);
    }

    public function equals(ListScreenType $type): bool
    {
        return $this->type === (string)$type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}