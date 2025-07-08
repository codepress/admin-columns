<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\CellRenderer;
use AC\TableScreen\ManageValueService;
use AC\Type\PostTypeSlug;
use DomainException;

class Post implements ManageValueService
{

    private PostTypeSlug $post_type;

    private int $priority;

    private CellRenderer $renderable;

    public function __construct(
        PostTypeSlug $post_type,
        CellRenderer $renderable,
        int $priority = 100
    ) {
        $this->post_type = $post_type;
        $this->renderable = $renderable;
        $this->priority = $priority;
    }

    public function register(): void
    {
        $action = sprintf("manage_%s_posts_custom_column", $this->post_type);

        if (did_action($action)) {
            throw new DomainException(sprintf("Method should be called before the %s action.", $action));
        }

        add_action($action, [$this, 'render_value'], $this->priority, 2);
    }

    public function render_value(...$args): void
    {
        [$column_id, $row_id] = $args;

        echo $this->renderable->render_cell((string)$column_id, $row_id);
    }

}