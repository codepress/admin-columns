<?php

namespace AC\ColumnSize;

use AC\Type\ColumnWidth;
use AC\Type\ListScreenId;

class UserStorage
{

    private const OPTION_UNIT = 'unit';
    private const OPTION_VALUE = 'value';

    private $user_preference;

    public function __construct(UserPreference $user_preference)
    {
        $this->user_preference = $user_preference;
    }

    public function save(ListScreenId $list_id, string $column_name, ColumnWidth $column_width): void
    {
        $widths = $this->user_preference->get($list_id->get_id());

        if ( ! $widths) {
            $widths = [];
        }

        $widths[$column_name] = [
            self::OPTION_UNIT  => $column_width->get_unit(),
            self::OPTION_VALUE => $column_width->get_value(),
        ];

        $this->user_preference->set(
            $list_id->get_id(),
            $widths
        );
    }

    public function exists(ListScreenId $list_id): bool
    {
        return null !== $this->user_preference->get($list_id->get_id());
    }

    public function get(ListScreenId $list_id, string $column_name): ?ColumnWidth
    {
        $widths = $this->user_preference->get(
            $list_id->get_id()
        );

        if ( ! isset($widths[$column_name])) {
            return null;
        }

        return new ColumnWidth(
            $widths[$column_name][self::OPTION_UNIT],
            $widths[$column_name][self::OPTION_VALUE]
        );
    }

    /**
     * @param ListScreenId $list_id
     *
     * @return ColumnWidth[]
     */
    public function get_all(ListScreenId $list_id): array
    {
        $widths = $this->user_preference->get(
            $list_id->get_id()
        );

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
        $widths = $this->user_preference->get(
            $list_id->get_id()
        );

        if ( ! $widths) {
            return;
        }

        unset($widths[$column_name]);

        $widths
            ? $this->user_preference->set($list_id->get_id(), $widths)
            : $this->delete_by_list_id($list_id);
    }

    public function delete_by_list_id(ListScreenId $list_id): void
    {
        $this->user_preference->delete(
            $list_id->get_id()
        );
    }

}