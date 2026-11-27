<?php

declare(strict_types=1);

namespace AC\ColumnPreview;

use AC\TableScreen;
use AC\Type\Value;

interface SampleItems
{
    public function supports(TableScreen $table_screen): bool;

    /**
     * @return Value[] most recent first
     */
    public function get_values(TableScreen $table_screen, int $limit): array;

    /**
     * @param int|string $id
     */
    public function get_label(TableScreen $table_screen, $id): string;

}
