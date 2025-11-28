<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\Value;
use DomainException;

class Comment implements ManageValueService
{

    private AC\Table\ManageValue\ValueFormatter $formatter;

    private int $priority;

    public function __construct(
        AC\Table\ManageValue\ValueFormatter $formatter,
        int $priority = 100
    ) {
        $this->formatter = $formatter;
        $this->priority = $priority;
    }

    public function register(): void
    {
        if (did_action('manage_comments_custom_column')) {
            throw new DomainException("Method should be called before the action triggers.");
        }

        add_action('manage_comments_custom_column', [$this, 'render_value'], $this->priority, 2);
    }

    public function render_value(...$args): void
    {
        [$column_id, $row_id] = $args;

        echo $this->formatter->format(
            new ColumnId((string)$column_id),
            new Value((int)$row_id, '')
        );
    }

}