<?php

declare(strict_types=1);

namespace AC\TableScreen\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\DefaultColumnsRepository;
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
     */
    public function register(): void
    {
        add_filter(sprintf('manage_%s_columns', $this->screen_id), [$this, 'handle'], $this->priority);
    }

    public function handle($headings): void
    {
        $this->repository->update(
            $this->list_key,
            $headings && is_array($headings) ? $headings : []
        );

        exit('ac_success');
    }

}