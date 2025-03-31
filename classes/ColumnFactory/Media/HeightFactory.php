<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;
use AC\Value\Formatter\Suffix;

class HeightFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-height';
    }

    public function get_label(): string
    {
        return __('Height', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AttachmentMetaData('height'));
        $formatters->add(new Suffix('px'));

        return $formatters;
    }
}