<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\MediaPreview;
use AC\Value\Formatter\Media\PreviewViewLink;

class PreviewFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-preview';
    }

    public function get_label(): string
    {
        return __('Preview', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PreviewViewLink(new MediaPreview()));

        return parent::get_formatters($components, $config, $formatters);
    }
}