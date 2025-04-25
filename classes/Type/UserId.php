<?php
declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class UserId
{

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        $this->validate();
    }

    private function validate(): void
    {
        if (0 >= $this->id) {
            throw new InvalidArgumentException('User id requires a positive integer.');
        }
    }

    public function get_value(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

}