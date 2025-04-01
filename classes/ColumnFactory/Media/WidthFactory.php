<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;
use AC\Value\Formatter\Suffix;

class WidthFactory extends ColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-width';
    }

    public function get_label(): string
    {
        return __('Width', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AttachmentMetaData('width'));
        $formatters->add(new Suffix('px'));

        return $formatters;
    }

}