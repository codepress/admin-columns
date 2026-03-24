<?php

namespace AC\ColumnSize;

use AC\Column;
use AC\Column\SettingUpdater;
use AC\ColumnIterator;
use AC\Type\ColumnId;
use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;
use InvalidArgumentException;

class ListStorage
{

    private SettingUpdater $setting_updater;

    public function __construct(SettingUpdater $setting_updater)
    {
        $this->setting_updater = $setting_updater;
    }

    public function save(ListScreenId $list_id, ColumnId $column_id, ColumnWidth $column_width): void
    {
        $this->setting_updater->update($list_id, $column_id, [
            'width'      => (string) $column_width->get_value(),
            'width_unit' => $column_width->get_unit(),
        ]);
    }

    public function get(Column $column): ?ColumnWidth
    {
        return $this->create($column);
    }

    /**
     * @return ColumnWidth[]
     */
    public function get_all(ColumnIterator $columns): array
    {
        $results = [];

        foreach ($columns as $column) {
            $width = $this->create($column);

            if ($width) {
                $results[(string) $column->get_id()] = $width;
            }
        }

        return $results;
    }

    private function create(Column $column): ?ColumnWidth
    {
        $width_setting = $column->get_setting('width');

        if ( ! $width_setting) {
            return null;
        }

        $width = (int) $width_setting->get_input()->get_value();

        if ($width < 1) {
            return null;
        }

        $unit = (string) $column->get_setting('width_unit')->get_input()->get_value();

        try {
            $width = new ColumnWidth($unit, $width);
        } catch (InvalidArgumentException $e) {
            return null;
        }

        return $width;
    }

}
