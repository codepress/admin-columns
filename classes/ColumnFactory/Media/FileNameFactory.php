<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Media\FileLinkToUrl;
use AC\Formatter\Media\FileName;
use AC\FormatterCollection;
use AC\Setting\Config;

class FileNameFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-file_name';
    }

    public function get_label(): string
    {
        return __('Filename', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new FileName());
        $formatters->add(new FileLinkToUrl());

        return $formatters;
    }

}