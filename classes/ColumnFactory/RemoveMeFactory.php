<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\ComponentFactory\CommentDisplay;
use AC\Setting\ComponentFactory\PostType;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;

class RemoveMeFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentDisplay $attachment_display,
        CommentDisplay $comment_display,
        PostType $post_type
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($attachment_display);
        $this->add_component_factory($comment_display);
        $this->add_component_factory($post_type);
    }

    public function get_type(): string
    {
        return 'column-removeme';
    }

    protected function get_label(): string
    {
        return __('Remove', 'codepress-admin-columns');
    }

}