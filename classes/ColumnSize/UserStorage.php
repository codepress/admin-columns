<?php

namespace AC\ColumnSize;

use AC\Preferences\SiteFactory;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class UserStorage
{

    private const OPTION_UNIT = 'unit';
    private const OPTION_VALUE = 'value';

    private $storage;

    public function __construct()
    {
        $this->storage = (new SiteFactory())->create('column_widths');
    }

    public function save(ListScreenId $list_id, string $column_name, ColumnWidth $column_width): void
    {
        $widths = $this->storage->find((string)$list_id) ?: [];

        $widths[$column_name] = [
            self::OPTION_UNIT  => $column_width->get_unit(),
            self::OPTION_VALUE => $column_width->get_value(),
        ];

        $this->storage->save(
            (string)$list_id,
            $widths
        );
    }

    public function exists(ListScreenId $list_id): bool
    {
        return null !== $this->storage->find((string)$list_id);
    }

    public function get(ListScreenId $list_id, ColumnId $column_id): ?ColumnWidth
    {
        $widths = $this->storage->find((string)$list_id);

        $name = (string)$column_id;

        if ( ! isset($widths[$name])) {
            return null;
        }

        return new ColumnWidth(
            $widths[$name][self::OPTION_UNIT],
            $widths[$name][self::OPTION_VALUE]
        );
    }

    /**
     * @param ListScreenId $list_id
     *
     * @return ColumnWidth[]
     */
    public function get_all(ListScreenId $list_id): array
    {
        $widths = $this->storage->find((string)$list_id);

        if ( ! $widths) {
            return [];
        }

        $column_widths = [];

        foreach ($widths as $column_name => $width) {
            $column_widths[$column_name] = new ColumnWidth(
                $width[self::OPTION_UNIT],
                $width[self::OPTION_VALUE]
            );
        }

        return $column_widths;
    }

    public function delete(ListScreenId $list_id, string $column_name): void
    {
        $widths = $this->storage->find(
            (string)$list_id
        );

        if ( ! $widths) {
            return;
        }

        unset($widths[$column_name]);

        $widths
            ? $this->storage->save((string)$list_id, $widths)
            : $this->delete_by_list_id($list_id);
    }

    public function delete_by_list_id(ListScreenId $list_id): void
    {
        $this->storage->delete(
            (string)$list_id
        );
    }

}