<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

class ListScreenStatus
{

    private const ACTIVE = '';
    private const INACTIVE = 'inactive';

    private string $status;

    public function __construct(string $status = null)
    {
        $this->status = $status ?? self::ACTIVE;
        $this->validate();
    }

    private function validate(): void
    {
        if ( ! in_array($this->status, [self::ACTIVE, self::INACTIVE], true)) {
            throw new InvalidArgumentException('Invalid list screen status');
        }
    }

    public static function create_active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function create_inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public function equals(ListScreenStatus $type): bool
    {
        return $this->status === (string)$type;
    }

    public function __toString(): string
    {
        return $this->status;
    }
}