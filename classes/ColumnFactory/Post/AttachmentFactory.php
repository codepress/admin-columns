<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;

class AttachmentFactory extends BaseColumnFactory
{

    private $attachments_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        AttachmentDisplay $attachments_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->attachments_factory = $attachments_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->attachments_factory);
    }

    public function get_column_type(): string
    {
        return 'column-attachment';
    }

    protected function get_label(): string
    {
        return __('Attachments', 'codepress-admin-columns');
    }

}