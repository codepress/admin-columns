<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Media\AttachmentMetaData;
use AC\Formatter\Suffix;
use AC\FormatterCollection;
use AC\Setting\Config;

class WidthFactory extends BaseColumnFactory
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