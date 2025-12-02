<?php

declare(strict_types=1);

namespace AC\TableScreen\ManageValue;

use AC\Table\ManageValue\RenderFactory;
use AC\TableScreen\ManageValueService;
use AC\Type\ColumnId;
use AC\Type\PostTypeSlug;
use AC\Type\Value;
use DomainException;

class Post implements ManageValueService
{

    private PostTypeSlug $post_type;

    private int $priority;

    private RenderFactory $factory;

    public function __construct(
        PostTypeSlug $post_type,
        RenderFactory $factory,
        int $priority = 100
    ) {
        $this->post_type = $post_type;
        $this->factory = $factory;
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

        $formatter = $this->factory->create(new ColumnId((string)$column_id));

        if ($formatter) {
            echo $formatter->format(new Value((int)$row_id));
        }
    }

}