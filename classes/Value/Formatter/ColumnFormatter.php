<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Sanitize\Kses;
use AC\Setting\Context;
use AC\Setting\Formatter;
use AC\TableScreen;
use AC\Type\ListScreenId;
use AC\Type\Value;

class ColumnFormatter implements Formatter
{

    public const DEFAULT = '&ndash;';

    private Context $context;

    private TableScreen $table_screen;

    private ListScreenId $list_id;

    private ?string $default;

    public function __construct(
        Context $context,
        TableScreen $table_screen,
        ListScreenId $list_id,
        ?string $default = null
    ) {
        $this->context = $context;
        $this->table_screen = $table_screen;
        $this->list_id = $list_id;
        $this->default = $default ?? self::DEFAULT;
    }

    public function format(Value $value): Value
    {
        if ($this->use_sanitize($value->get_id())) {
            $value = $this->sanitize_value($value);
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

        if ('' === (string)$value) {
            return $value->with_value($this->default);
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

    private function sanitize_value(Value $value): Value
    {
        return $value->with_value(
            (new Kses())->sanitize((string)$value)
        );
    }

}