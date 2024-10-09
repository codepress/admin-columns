<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageHeading;

use AC\Registerable;

class WpListTable implements Registerable
{

    private string $screen_id;

    private array $headings;

    private int $priority;

    public function __construct(string $screen_id, array $headings, int $priority = 200)
    {
        $this->screen_id = $screen_id;
        $this->headings = $headings;
        $this->priority = $priority;
    }

    /**
     * @see WP_List_Table::__construct()
     */
    public function register(): void
    {
        add_filter(sprintf('manage_%s_columns', $this->screen_id), [$this, 'handle'], $this->priority);
    }

    public function handle($current_headings): array
    {
        $headings = $this->headings;
        $checkbox = $current_headings['cb'] ?? null;

        if ($checkbox) {
            $headings = array_merge(['cb' => $checkbox], $headings);
        }

        return $headings;
    }

}