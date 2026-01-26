<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\AttachmentDisplay;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class AttachmentFactory extends BaseColumnFactory
{

    private AttachmentDisplay $attachments_factory;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        AttachmentDisplay $attachments_factory
    ) {
        parent::__construct($default_settings_builder);

        $this->attachments_factory = $attachments_factory;
    }

    protected function get_settings(Config $config): AC\Setting\ComponentCollection
    {
        return new AC\Setting\ComponentCollection([
            $this->attachments_factory->create($config),
        ]);
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