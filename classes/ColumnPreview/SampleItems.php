<?php

declare(strict_types=1);

namespace AC\ColumnPreview;

use AC\TableScreen;

interface SampleItems
{
    public function supports(TableScreen $table_screen): bool;

    /**
     * @return int[] most recent first
     */
    public function get_ids(TableScreen $table_screen, int $limit): array;

    public function get_label(TableScreen $table_screen, int $id): string;

}
