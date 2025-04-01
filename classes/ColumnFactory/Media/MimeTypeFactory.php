<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\PostMimeType;

class MimeTypeFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-mime_type';
    }

    public function get_label(): string
    {
        return __('Mime Type', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new PostMimeType());

        return $formatters;
    }

}