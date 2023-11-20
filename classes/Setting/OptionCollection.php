<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Option;
use Iterator;

final class OptionCollection implements Iterator
{

    private $data = [];

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public static function from_values(array $data): self
    {
        $self = new self();

        foreach ($data as $value) {
            $self->add(Option::from_value($value));
        }

        return $self;
    }

    public function add(Option $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Option
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): int
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

}