<?php

namespace AC\Type;

use InvalidArgumentException;

class ColumnWidth
{

    private string $unit;

    private int $value;

    public function __construct(string $unit, int $value)
    {
        $this->unit = $unit;
        $this->value = $value;

        $this->validate();
    }

    private function validate()
    {
        if ( ! in_array($this->unit, ['px', '%'])) {
            throw new InvalidArgumentException('Invalid width unit.');
        }
        if ($this->value < 0) {
            throw new InvalidArgumentException('Invalid width.');
        }
    }

    public function get_unit(): string
    {
        return $this->unit;
    }

    public function get_value(): int
    {
        return $this->value;
    }

}