<?php

declare(strict_types=1);

namespace AC\Type;

use AC\Collection;

class OriginalColumns extends Collection
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public static function create_from_headings(array $headings): self
    {
        $columns = [];

        foreach ($headings as $column_name => $label) {
            if ('cb' === $column_name) {
                continue;
            }

            if (OriginalColumn::validate($column_name, $label)) {
                $columns[] = new OriginalColumn((string)$column_name, (string)$label);
            }
        }

        return new self($columns);
    }

    public function add(OriginalColumn $component): self
    {
        $this->data[] = $component;

        return $this;
    }

    public function current(): OriginalColumn
    {
        return current($this->data);
    }

}