<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Value;

final class ValueCollection extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public static function from_ids(array $data): self
    {
        $self = new self();

        foreach ($data as $id) {
            $self->add(new Value((int)$id));
        }

        return $self;
    }

    public function add(Value $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Value
    {
        return parent::current();
    }

}