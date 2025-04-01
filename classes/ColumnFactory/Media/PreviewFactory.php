<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\MediaPreview;
use AC\Value\Formatter\Media\PreviewViewLink;

class PreviewFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-preview';
    }

    public function get_label(): string
    {
        return __('Preview', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new PreviewViewLink(new MediaPreview()));

        return $formatters;
    }

}