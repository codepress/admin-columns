<?php

declare(strict_types=1);

namespace AC\TableScreen\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Type\DefaultColumn;
use AC\Type\DefaultColumns;
use AC\Type\ListKey;

class ScreenColumns implements Registerable
{

    private string $screen_id;

    private DefaultColumnsRepository $repository;

    private ListKey $list_key;

    private int $priority;

    public function __construct(
        string $screen_id,
        ListKey $list_key,
        DefaultColumnsRepository $repository,
        int $priority = 199
    ) {
        $this->screen_id = $screen_id;
        $this->list_key = $list_key;
        $this->repository = $repository;
        $this->priority = $priority;
    }

    /**
     * @see get_column_headers()
     * @see WP_List_Table::get_column_info()
     */
    public function register(): void
    {
        add_filter(
            sprintf('manage_%s_columns', $this->screen_id),
            [$this, 'save_columns'],
            $this->priority
        );
        add_filter(
            sprintf('manage_%s_sortable_columns', $this->screen_id),
            [$this, 'save_sortable_columns'],
            $this->priority
        );
    }

    public function save_columns($headings)
    {
        $columns = new DefaultColumns();

        foreach ($headings as $column_name => $label) {
            if ('cb' === $column_name) {
                continue;
            }

            $columns->add(new DefaultColumn($column_name, $label));
        }

        $this->save($columns);

        return $headings;
    }

    public function save(DefaultColumns $columns)
    {
        $this->repository->update($this->list_key, $columns);
    }

    public function save_sortable_columns($sortable_columns)
    {
        $columns = new DefaultColumns();

        $sortables = array_keys($sortable_columns);

        foreach ($this->repository->find_all($this->list_key) as $column) {
            $is_sortable = in_array($column->get_name(), $sortables, true);

            $columns->add(
                $column->with_sortable($is_sortable)
            );
        }

        $this->save($columns);

        exit('ac_success');
    }

}