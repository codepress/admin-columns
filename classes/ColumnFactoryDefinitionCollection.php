<?php

declare(strict_types=1);

namespace AC;

use AC\Type\ColumnFactoryDefinition;

final class ColumnFactoryDefinitionCollection extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(ColumnFactoryDefinition $definition): void
    {
        $this->data[] = $definition;
    }

    public function current(): ColumnFactoryDefinition
    {
        return current($this->data);
    }

}