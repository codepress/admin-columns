<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Column;
use AC\Formatter\Aggregate;
use AC\FormatterCollection;
use AC\ListScreen;
use AC\Type;
use AC\Value\ExtendedValueLink;
use AC\Value\Formatter;

class Value implements ExtendedValue
{

    public function render(
        $id,
        array $params,
        Column $column,
        ListScreen $list_screen
    ): string {
        $formatters = [];

        foreach ($column->get_formatters() as $formatter) {
            if ($formatter instanceof \AC\Formatter\ExtendedValueLink) {
                continue;
            }

            $formatters[] = $formatter;
        }

        $formatter = new Aggregate(new FormatterCollection($formatters));

        return (string)$formatter->format(
            new Type\Value($id)
        );
    }

    public function can_render(string $view): bool
    {
        return $view === 'value';
    }

    public function get_link($id, string $label): ExtendedValueLink
    {
        return new ExtendedValueLink($label, $id, 'value');
    }

}