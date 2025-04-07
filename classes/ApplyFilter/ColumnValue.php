<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\ListScreen;
use AC\Setting\Context;

class ColumnValue
{

    private $id;

    private Context $context;

    private ListScreen $list_screen;

    public function __construct(Context $context, $id, ListScreen $list_screen)
    {
        $this->id = $id;
        $this->context = $context;
        $this->list_screen = $list_screen;
    }

    public function apply_filter(string $value = null): ?string
    {
        $value = apply_filters(
            'ac/v2/column/value',
            $value,
            $this->id,
            $this->context,
            $this->list_screen->get_table_screen(),
            $this->list_screen->get_id()
        );

        return is_scalar($value)
            ? (string)$value
            : null;
    }

}