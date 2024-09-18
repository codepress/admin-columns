<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\PostMimeType;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new PostMimeType());
    }

}