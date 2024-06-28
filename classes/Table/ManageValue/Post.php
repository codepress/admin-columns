<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Table\ColumnRenderable;
use AC\Table\ManageValue;
use DomainException;

class Post extends ManageValue
{

    private $post_type;

    private $renderable;

    public function __construct(string $post_type, ColumnRenderable $renderable)
    {
        $this->post_type = $post_type;
        $this->renderable = $renderable;
    }

    /**
     * @see WP_Posts_List_Table::column_default
     */
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
        echo $this->renderable->render((string)$column_name, (int)$id);
    }

}