<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\ValueFormatter;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\Value;
use DomainException;

class Media implements ManageValueService
{

    private ValueFormatter $formatter;

    private int $priority;

    public function __construct(
        ValueFormatter $formatter,
        int $priority = 100
    ) {
        $this->formatter = $formatter;
        $this->priority = $priority;
    }

    public function register(): void
    {
        if (did_action('manage_media_custom_column')) {
            throw new DomainException("Method should be called before the action triggers.");
        }

        add_action('manage_media_custom_column', [$this, 'render_value'], $this->priority, 2);
    }

    public function render_value(...$args): void
    {
        [$column_id, $row_id] = $args;

        echo $this->formatter->format(
            new ColumnId($column_id),
            new Value($row_id, '')
        );
    }

}