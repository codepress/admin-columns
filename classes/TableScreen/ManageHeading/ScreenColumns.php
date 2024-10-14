<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageHeading;

use AC\Registerable;

class ScreenColumns implements Registerable
{

    private string $screen_id;

    /**
     * @var array [ $column_id => $label, ... ]
     */
    private array $headings;

    private int $priority;

    public function __construct(string $screen_id, array $headings, int $priority = 200)
    {
        $this->screen_id = $screen_id;
        $this->headings = $headings;
        $this->priority = $priority;
    }

    /**
     * @see get_column_headers()
     */
    public function register(): void
    {
        add_filter(sprintf('manage_%s_columns', $this->screen_id), [$this, 'handle'], $this->priority);
    }

    public function handle($current_headings): array
    {
        exit;
        $headings = $this->headings;
        $checkbox = $current_headings['cb'] ?? null;

        if ($checkbox) {
            $headings = ['cb' => $checkbox] + $headings;
        }

        return $headings;
    }

}