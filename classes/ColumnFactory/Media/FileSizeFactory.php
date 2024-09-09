<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\FileSize;

class FileSizeFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-file_size';
    }

    public function get_label(): string
    {
        return __('File Size', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new FileSize());

        return parent::get_formatters($components, $config, $formatters);
    }

}