<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Column;
use AC\Registerable;
use AC\Table\ColumnRenderable;
use AC\Type\PostTypeSlug;
use DomainException;

// TODO Proof-of-concept
class Post implements Registerable
{

    private Column $column;

    private PostTypeSlug $post_type;

    private int $priority;

    public function __construct(PostTypeSlug $post_type, Column $column, int $priority = 100)
    {
        $this->column = $column;
        $this->post_type = $post_type;
        $this->priority = $priority;
    }

    public function register(): void
    {
        $action = sprintf("manage_%s_posts_custom_column", $this->post_type);

        if (did_action($action)) {
            throw new DomainException(sprintf("Method should be called before the %s action.", $action));
        }

        add_action($action, [$this, 'manage_value'], $this->priority, 2);
    }

    public function manage_value($column_id, $row_id): void
    {
        if ((string)$this->column->get_id() !== (string)$column_id) {
            return;
        }

        echo ColumnRenderable::render($this->column, (int)$row_id);
    }

}