<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Column\Context;
use AC\Formatter;
use AC\ListScreen;
use AC\TableScreen;
use AC\Type\Value;

class ColumnFilter implements Formatter
{

    private Context $context;

    private TableScreen $table_screen;

    private ListScreen $list_screen;

    public function __construct(
        Context $context,
        TableScreen $table_screen,
        ListScreen $list_screen
    ) {
        $this->context = $context;
        $this->table_screen = $table_screen;
        $this->list_screen = $list_screen;
    }

    public function format(Value $value): Value
    {
        if ($this->use_sanitize($value->get_id())) {
            $value = (new Kses())->format($value);
        }

        $render = apply_filters(
            'ac/column/render',
            $value->get_value(),
            $this->context,
            $value->get_id(),
            $this->table_screen,
            $this->list_screen
        );

        if (is_scalar($render)) {
            $value = $value->with_value($render);
        }

        return $value;
    }

    private function use_sanitize($id): bool
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