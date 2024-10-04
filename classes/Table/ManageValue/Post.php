<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\Registerable;
use AC\Table\GridRenderable;
use AC\Type\PostTypeSlug;
use DomainException;

// TODO Proof-of-concept
class Post implements Registerable
{

    private PostTypeSlug $post_type;

    private int $priority;

    private GridRenderable $renderable;

    public function __construct(
        PostTypeSlug $post_type,
        GridRenderable $renderable,
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

        add_action($action, [$this, 'manage_value'], $this->priority, 2);
    }

    public function manage_value($column_id, $row_id): void
    {
        echo $this->renderable->render($column_id, $row_id);
    }

}