<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Linkable;
use AC\Setting\Formatter\Media\AttachmentUrl;
use AC\Setting\FormatterCollection;

class PreviewFactory extends ColumnFactory
{
    public function get_type(): string
    {
        return 'column-preview';
    }

    protected function get_label(): string
    {
        return __('Preview', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentUrl());
        $formatters->add(new Linkable('View'));

        return parent::get_formatters($components, $config, $formatters);
    }
}