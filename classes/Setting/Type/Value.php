<?php

declare(strict_types=1);

namespace AC\Setting\Type;

final class Value
{

    private $id;

    private $value;

    public function __construct($id, $value = null)
    {
        if (null === $value) {
            $value = $id;
        }

        $this->id = $id;
        $this->value = $value;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function with_value($value): self
    {
        return new self($this->id, $value);
    }

    public function get_value()
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

}