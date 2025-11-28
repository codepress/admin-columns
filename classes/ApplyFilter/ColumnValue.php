<?php

declare(strict_types=1);

namespace AC\ApplyFilter;

use AC\ListScreen;
use AC\Setting\Context;
use AC\Type\Value;

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

    public function apply_filter(Value $value): Value
    {
        $render = apply_filters(
            'ac/column/render',
            $value->get_value(),
            $this->context,
            $this->id,
            $this->list_screen->get_table_screen(),
            $this->list_screen->get_id()
        );

        if (is_scalar($render)) {
            $value = $value->with_value($render);
        }

        return $value;
    }

}