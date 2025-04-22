<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

class DefaultColumns extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public static function create_by_headings(array $headings): self
    {
        $columns = [];

        foreach ($headings as $column_name => $label) {
            if ('cb' === $column_name) {
                continue;
            }

            if (DefaultColumn::validate($column_name, $label)) {
                $columns[] = new DefaultColumn((string)$column_name, (string)$label);
            }
        }

        return new self($columns);
    }

    public function add(DefaultColumn $component): self
    {
        $this->data[] = $component;

        return $this;
    }

    public function current(): DefaultColumn
    {
        return current($this->data);
    }

}