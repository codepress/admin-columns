<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Setting\Context;
use AC\TableScreen;
use AC\Type\ListScreenId;

class ColumnValueSanitize
{

    private $id;

    private Context $context;

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    public function __construct(Context $context, $id, TableScreen $table_screen, ListScreenId $list_id)
    {
        $this->id = $id;
        $this->context = $context;
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
    }

    public function apply_filter(bool $sanitize = true): bool
    {
        return (bool)apply_filters(
            'ac/column/render/sanitize',
            $sanitize,
            $this->context,
            $this->id,
            $this->table_screen,
            $this->list_id
        );
    }

}