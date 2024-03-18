<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\ComponentFactoryRegistry;

class AttachmentFactory extends ColumnFactory
{

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentDisplay $attachments_factory
    ) {
        parent::__construct($component_factory_registry);

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