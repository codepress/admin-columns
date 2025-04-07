<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Column;
use AC\ListScreen;
use AC\Setting\ContextFactory;
use AC\Setting\FormatterCollection;
use AC\Table\ManageValue\ColumnRenderable;
use AC\Value\ExtendedValueLink;
use AC\Value\Formatter;

class Value implements ExtendedValue
{

    private ContextFactory $context_factory;

    public function __construct(
        ContextFactory $context_factory
    ) {
        $this->context_factory = $context_factory;
    }

    public function render(
        int $id,
        array $params,
        Column $column,
        ListScreen $list_screen
    ): string {
        $formatters = [];

        foreach ($column->get_formatters() as $formatter) {
            if ($formatter instanceof Formatter\ExtendedValueLink) {
                continue;
            }

            $formatters[] = $formatter;
        }

        $renderable = new ColumnRenderable(
            new FormatterCollection($formatters),
            $this->context_factory->create($column),
            $list_screen
        );

        ob_start();

        echo $renderable->render($id);

        return (string)ob_get_clean();
    }

    public function can_render(string $view): bool
    {
        return $view === 'value';
    }

    public function get_link(int $id, string $label): ExtendedValueLink
    {
        return new ExtendedValueLink($label, $id, 'value');
    }

}