<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Type\Condition;

final class ConditionCollection extends Collection
{

    public function __construct(array $data)
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

}