<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Context;
use AC\Setting\Formatter;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;

class ColumnFilter implements Formatter
{

    private Context $context;

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    private ?string $default;

    public function __construct(
        Context $context,
        TableScreen $table_screen,
        ListScreenId $list_id
    ) {
        $this->context = $context;
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
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
            $this->list_id
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
            $this->list_id
        );
    }

}