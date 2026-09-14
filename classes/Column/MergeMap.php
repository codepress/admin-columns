<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\ColumnIterator;
use AC\Type\ColumnId;

/**
 * Groups columns that render inside the cell of the column above them.
 */
final class MergeMap
{
    /**
     * @var array<string, Column[]>
     */
    private array $members = [];

    /**
     * @var array<string, true>
     */
    private array $merged = [];

    public static function create(ColumnIterator $columns): self
    {
        $self = new self();
        $leader = null;

        foreach ($columns as $column) {
            // A column the list table renders itself can neither carry a merged value nor be merged away.
            if (! self::is_rendered_by_us($column)) {
                $leader = null;

                continue;
            }

            if (null !== $leader && MergeSettings::is_enabled($column)) {
                $self->members[$leader][] = $column;
                $self->merged[(string)$column->get_id()] = true;

                continue;
            }

            $leader = (string)$column->get_id();
            $self->members[$leader] = [];
        }

        return $self;
    }

    public function is_merged(ColumnId $id): bool
    {
        return isset($this->merged[(string)$id]);
    }

    /**
     * @return Column[]
     */
    public function get_members(ColumnId $id): array
    {
        return $this->members[(string)$id] ?? [];
    }

    private static function is_rendered_by_us(Column $column): bool
    {
        return $column->get_formatters()->count() > 0;
    }

}
