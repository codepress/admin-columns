<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Option;
use Iterator;

final class OptionCollection implements Iterator
{

    private $data = [];

    public function __construct(array $options = [])
    {
        array_map([$this, 'add'], $options);
    }

    public static function from_array(array $options, bool $associative = true): self
    {
        $self = new self();

        foreach ($options as $key => $value) {
            if ( ! $associative) {
                $key = $value;
            }

            $self->add(new Option($value, $key));
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