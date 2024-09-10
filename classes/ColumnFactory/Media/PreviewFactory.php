<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new PreviewViewLink(new MediaPreview()));
    }

}