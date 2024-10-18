<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Setting\Collection;
use Countable;

final class ValueCollection extends Collection implements Countable
{

    private $id;

    public function __construct($id, array $data = [])
    {
        $this->id = (int)$id;

        array_map([$this, 'add'], $data);
    }

    public function get_id(): int
    {
        return $this->id;
    }

    public static function from_ids($id, array $data): self
    {
        $self = new self($id);

        $add = static function ($id) use ($self) {
            $self->add(new Value((int)$id));
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

    public function count(): int
    {
        return count($this->data);
    }

}