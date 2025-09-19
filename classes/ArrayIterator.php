<?php

namespace AC;

class ArrayIterator extends Collection
{

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->data);
    }

    public function get_offset($offset)
    {
        return $this->data[$offset];
    }

    public function has_offset($offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function search($value): bool
    {
        return in_array($value, $this->data, true);
    }

    public function get_copy(): array
    {
        $copy = $this->data;

        reset($copy);

        return $copy;
    }

}