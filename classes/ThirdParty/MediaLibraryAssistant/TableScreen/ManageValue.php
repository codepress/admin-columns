<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant\TableScreen;

use AC;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\Value;

class ManageValue implements ManageValueService
{

    private AC\Table\ManageValue\ValueFormatter $formatter;

    private int $priority;

    public function __construct(AC\Table\ManageValue\ValueFormatter $formatter, int $priority = 100)
    {
        $this->formatter = $formatter;
        $this->priority = $priority;
    }

    public function register(): void
    {
        add_filter('mla_list_table_column_default', [$this, 'render_value'], $this->priority, 3);
    }

    public function render_value(...$args)
    {
        [$value, $post, $column_name] = $args;
        
        if (is_null($value)) {
            return (string)$this->formatter->format(
                new ColumnId((string)$column_name),
                new Value((int)$post->ID, '')
            );
        }

        return $value;
    }

}