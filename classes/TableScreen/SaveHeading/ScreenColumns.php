<?php

declare(strict_types=1);

namespace AC\TableScreen\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Type\DefaultColumns;
use AC\Type\TableId;

class ScreenColumns implements Registerable
{

    private string $screen_id;

    private DefaultColumnsRepository $repository;

    private TableId $table_id;

    private int $priority;

    private bool $do_exit;

    public function __construct(
        string $screen_id,
        TableId $table_id,
        DefaultColumnsRepository $repository,
        int $priority = 199,
        bool $do_exit = true
    ) {
        $this->screen_id = $screen_id;
        $this->table_id = $table_id;
        $this->repository = $repository;
        $this->priority = $priority;
        $this->do_exit = $do_exit;
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
        if ($headings && is_array($headings)) {
            $this->save(
                DefaultColumns::create_by_headings($headings)
            );
        }

        return $headings;
    }

    public function save(DefaultColumns $columns): void
    {
        $this->repository->update($this->table_id, $columns);
    }

    public function save_sortable_columns($sortable_columns): void
    {
        $columns = new DefaultColumns();

        $sortables = array_keys($sortable_columns);

        foreach ($this->repository->find_all($this->table_id) as $column) {
            $is_sortable = in_array($column->get_name(), $sortables, true);

            $columns->add(
                $column->with_sortable($is_sortable)
            );
        }

        $this->save($columns);

        if ($this->do_exit) {
            ob_clean();
            exit('ac_success');
        }
    }

}