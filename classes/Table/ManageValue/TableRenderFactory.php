<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Column\MergeMap;
use AC\Column\MergeSettings;
use AC\ColumnRepository\Sort\ManualOrder;
use AC\Formatter;
use AC\Formatter\Merge;
use AC\Formatter\TableRender;
use AC\FormatterCollection;
use AC\ListScreen;
use AC\TableScreen;
use AC\Type\ColumnId;

class TableRenderFactory implements RenderFactory
{
    private ListScreen $list_screen;

    private TableScreen $table_screen;

    private ?MergeMap $merge_map = null;

    public function __construct(ListScreen $list_screen)
    {
        $this->list_screen = $list_screen;
        $this->table_screen = $list_screen->get_table_screen();
    }

    public function create(ColumnId $columnId): ?Formatter
    {
        $column = $this->list_screen->get_column($columnId);

        if (! $column) {
            return null;
        }

        $merge_map = $this->get_merge_map();

        // A merged column renders inside the cell of its leader, never in one of its own.
        if ($merge_map->is_merged($columnId)) {
            return null;
        }

        $formatters = $column->get_formatters();
        $members = $merge_map->get_members($columnId);

        if ($members) {
            $formatters = FormatterCollection::from_formatter(
                new Merge($formatters, $this->create_members($members))
            );
        }

        if (0 === $formatters->count()) {
            return null;
        }

        return new TableRender(
            $formatters,
            $column->get_context(),
            $this->table_screen,
            $this->list_screen
        );
    }

    /**
     * @param Column[] $members
     */
    private function create_members(array $members): array
    {
        $created = [];

        foreach ($members as $member) {
            $created[] = [
                'separator'  => MergeSettings::get_separator($member),
                'formatters' => $member->get_formatters(),
            ];
        }

        return $created;
    }

    private function get_merge_map(): MergeMap
    {
        if (null === $this->merge_map) {
            $this->merge_map = MergeMap::create(
                (new ManualOrder($this->list_screen->get_id()))->sort($this->list_screen->get_columns())
            );
        }

        return $this->merge_map;
    }

}
