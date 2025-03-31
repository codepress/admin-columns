<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;

class AttachmentFactory extends BaseColumnFactory
{

    private AttachmentDisplay $attachments_factory;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        AttachmentDisplay $attachments_factory
    ) {
        parent::__construct($base_settings_builder);

        $this->attachments_factory = $attachments_factory;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->attachments_factory);
    }

    public function get_column_type(): string
    {
        return 'column-attachment';
    }

    public function get_label(): string
    {
        return __('Attachments', 'codepress-admin-columns');
    }

}