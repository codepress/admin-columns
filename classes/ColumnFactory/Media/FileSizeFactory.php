<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\FileSize;
use AC\Value\Formatter\ReadableFileSize;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new FileSize());
        $formatters->add(new ReadableFileSize());

        return $formatters;
    }

}