<?php

declare(strict_types=1);

namespace AC\Table\Service;

use AC\Column;
use AC\Registerable;
use AC\Table\ColumnRenderable;
use AC\Type\ColumnId;
use AC\Type\PostTypeSlug;
use DomainException;

// TODO Proof-of-concept
class Post implements Registerable
{

    private Column $column;

    private PostTypeSlug $post_type;

    public function __construct(PostTypeSlug $post_type, Column $column)
    {
        $this->column = $column;
        $this->post_type = $post_type;
    }

    public function register(): void
    {
        $action = sprintf("manage_%s_posts_custom_column", $this->post_type);

        if (did_action($action)) {
            throw new DomainException(sprintf("Method should be called before the %s action.", $action));
        }

        add_action($action, [$this, 'manage_value'], 100, 2);
    }

    public function manage_value($column_name, $id): void
    {
        if ( ! $this->column->get_id()->equals(new ColumnId((string)$column_name))) {
            return;
        }

        echo (new ColumnRenderable($this->column))->render($id);
    }

}