<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\Column\Context;
use AC\ListScreen;
use AC\TableScreen;

class RenderSanitize
{
    private Context $context;

    private TableScreen $table_screen;

    private ListScreen $list_screen;

    public function __construct(Context $context, TableScreen $table_screen, ListScreen $list_screen)
    {
        $this->context = $context;
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
    }

    public function apply_filters($id): bool
    {
        return (bool)apply_filters(
            'ac/column/render/sanitize',
            true,
            $this->context,
            $id,
            $this->table_screen,
            $this->list_screen
        );
    }

}
