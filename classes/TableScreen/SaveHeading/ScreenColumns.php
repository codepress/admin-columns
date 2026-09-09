<?php

declare(strict_types=1);

namespace AC\TableScreen\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\OriginalColumnsRepository;
use AC\Type\OriginalColumns;
use AC\Type\TableId;

class ScreenColumns implements Registerable
{
    private string $screen_id;

    private OriginalColumnsRepository $repository;

    private TableId $table_id;

    private bool $do_exit;

    private int $priority;

    private ?array $headings = null;

    private bool $saved = false;

    public function __construct(
        string $screen_id,
        TableId $table_id,
        OriginalColumnsRepository $repository,
        bool $do_exit = true,
        int $priority = 199
    ) {
        $this->screen_id = $screen_id;
        $this->table_id = $table_id;
        $this->repository = $repository;
        $this->do_exit = $do_exit;
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
            [$this, 'read_columns'],
            $this->priority
        );
        add_filter(
            sprintf('manage_%s_sortable_columns', $this->screen_id),
            [$this, 'save_sortable_columns'],
            $this->priority
        );
    }

    public function read_columns($headings)
    {
        if (null === $this->headings && $headings && is_array($headings)) {
            $this->headings = $headings;
        }

        return $headings;
    }

    public function save_sortable_columns($sortable_columns)
    {
        if ($this->saved || ! is_array($sortable_columns)) {
            return $sortable_columns;
        }

        $this->saved = true;

        // Headings come from the same request. Fall back to what is stored when that filter did not run.
        $columns = null === $this->headings
            ? $this->repository->find_all($this->table_id)
            : OriginalColumns::create_from_headings($this->headings);

        $sortables = array_keys($sortable_columns);

        foreach ($columns as $column) {
            $column->set_sortable(
                in_array($column->get_name(), $sortables, true)
            );
        }

        $this->repository->update(
            $this->table_id,
            $columns
        );

        if ($this->do_exit) {
            ob_clean();
            exit('ac_success');
        }

        return $sortable_columns;
    }

}
