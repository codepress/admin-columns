<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Option;

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
        return parent::current();
    }

}