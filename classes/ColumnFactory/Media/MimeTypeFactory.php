<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Media\PostMimeType;
use AC\FormatterCollection;
use AC\Setting\Config;

class MimeTypeFactory extends BaseColumnFactory
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