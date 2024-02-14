<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Settings\Column\AttachmentsFactory;

class AttachmentFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentsFactory $attachments_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($attachments_factory);
    }

    public function get_type(): string
    {
        return 'column-attachment';
    }

    protected function get_label(): string
    {
        return __('Attachments', 'codepress-admin-columns');
    }

}