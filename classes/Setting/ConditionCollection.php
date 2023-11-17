<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Condition;
use Countable;

final class ConditionCollection extends Collection implements Countable
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Condition $item): void
    {
        $this->data[] = $item;
    }

    public function current(): Condition
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }

}