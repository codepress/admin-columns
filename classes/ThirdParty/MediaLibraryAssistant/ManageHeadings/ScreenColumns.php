<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\ManageHeadings;

use AC\Registerable;

//TODO probably not neede anymore since the default ScreenColumns should work also
class ScreenColumns implements Registerable
{

    /**
     * @var array [ $column_id => $label, ... ]
     */
    private array $headings;

    public function __construct(array $headings)
    {
        $this->headings = $headings;
    }

    /**
     * @see get_column_headers()
     */
    public function register(): void
    {
        // TODO this hook does not fire, even the manage-%s-columns does not work.
        add_filter('mla_list_table_get_columns', [$this, 'handle'], 200);
    }

    public function handle($current_headings): array
    {
        $headings = $this->headings;
        $checkbox = $current_headings['cb'] ?? null;

        if ($checkbox) {
            $headings = ['cb' => $checkbox] + $headings;
        }

        return $headings;
    }

}