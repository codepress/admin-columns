<?php

namespace AC\ColumnSize;

use AC\Preferences\Preference;
use AC\Preferences\SiteFactory;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class UserStorage
{

    private const OPTION_UNIT = 'unit';
    private const OPTION_VALUE = 'value';

    private SiteFactory $storage_factory;

    public function __construct(SiteFactory $storage_factory)
    {
        $this->storage_factory = $storage_factory;
    }

    private function create_storage(): Preference
    {
        return $this->storage_factory->create('column_widths');
    }

    public function save(ListScreenId $list_id, ColumnId $column_name, ColumnWidth $column_width): void
    {
        $widths = $this->create_storage()->find((string)$list_id) ?: [];

        $widths[(string)$column_name] = [
            self::OPTION_UNIT  => $column_width->get_unit(),
            self::OPTION_VALUE => $column_width->get_value(),
        ];

        $this->create_storage()->save(
            (string)$list_id,
            $widths
        );
    }

    public function exists(ListScreenId $list_id): bool
    {
        return null !== $this->create_storage()->find((string)$list_id);
    }

    public function get(ListScreenId $list_id, ColumnId $column_id): ?ColumnWidth
    {
        $widths = $this->create_storage()->find((string)$list_id);

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
        $widths = $this->create_storage()->find((string)$list_id);

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
        $widths = $this->create_storage()->find(
            (string)$list_id
        );

        if ( ! $widths) {
            return;
        }

        unset($widths[$column_name]);

        $widths
            ? $this->create_storage()->save((string)$list_id, $widths)
            : $this->delete_by_list_id($list_id);
    }

    public function delete_by_list_id(ListScreenId $list_id): void
    {
        $this->create_storage()->delete(
            (string)$list_id
        );
    }

}