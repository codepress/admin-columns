<?php

declare(strict_types=1);

namespace AC\Setting\Control;

use AC\Setting\Collection;
use AC\Setting\Control\Type\Option;

final class OptionCollection extends Collection
{

    public function __construct(array $options = [])
    {
        array_map([$this, 'add'], $options);
    }

    public static function from_array(array $data, bool $associative = true): self
    {
        $self = new self();

        foreach ($data as $key => $value) {
            if ( ! $associative) {
                $key = $value;
            }

            $self->add(new Option((string)$value, $key));
        }

        return $self;
    }

    public function contains(string $value): bool
    {
        return $this->find($value) !== null;
    }

    public function find(string $value): ?Option
    {
        foreach ($this->data as $option) {
            if ($option->get_value() === $value) {
                return $option;
            }
        }

        return null;
    }

    public function add(Option $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Option
    {
        return parent::current();
    }

}