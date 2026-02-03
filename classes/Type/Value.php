<?php

declare(strict_types=1);

namespace AC\Type;

final class Value
{

    /**
     * @var mixed The unique identifier of the value.
     */
    private $id;

    /**
     * @var mixed The actual value.
     */
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
        return is_scalar($this->value)
            ? (string)$this->value
            : '';
    }

}