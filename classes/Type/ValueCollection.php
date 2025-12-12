<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

final class ValueCollection extends Collection
{

    private $id;

    public function __construct($id, array $data = [])
    {
        $this->id = $id;

        array_map([$this, 'add'], $data);
    }

    public function get_id()
    {
        return $this->id;
    }

    public static function from_ids($id, array $data): self
    {
        $self = new self($id);

        $add = static function ($id) use ($self) {
            $self->add(new Value($id));
        };

        array_map($add, $data);

        return $self;
    }

    public function add(Value $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Value
    {
        return current($this->data);
    }

}