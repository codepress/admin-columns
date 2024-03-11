<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\ComponentFactory\CommentStatus;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;

class RemoveMeFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentDisplay $attachment_display,
        CommentStatus $comment_status
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        //$this->add_component_factory($attachment_display);
        $this->add_component_factory($comment_status);
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