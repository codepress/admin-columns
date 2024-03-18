<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\ComponentFactory\CommentStatus;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\FormatterCollection;

class RemoveMeFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentDisplay $attachment_display,
        CommentStatus $comment_status
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($attachment_display);
    }

    protected function create_formatters(Config $config): FormatterCollection
    {
        return new FormatterCollection([
            new Formatter\Post\FeaturedImage(),
            // TODO implement setting formatter (Fetch)
        ]);
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