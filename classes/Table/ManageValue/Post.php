<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\ListScreen;
use AC\Table\ManageValue;
use DomainException;

class Post extends ManageValue
{

    private $post_type;

    public function __construct(string $post_type, ListScreen $list_screen)
    {
        parent::__construct($list_screen);

        $this->post_type = $post_type;
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

        add_action($action, [$this, 'render_value'], 100, 2);
    }

    public function render_value($column_name, $id): void
    {
        echo $this->render_cell((string)$column_name, (int)$id);
    }

}