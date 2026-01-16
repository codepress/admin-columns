<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Media\AttachmentMetaData;
use AC\FormatterCollection;
use AC\Setting\Config;

class AlbumFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-meta_album';
    }

    public function get_label(): string
    {
        return __('Album', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AttachmentMetaData('album'));

        return $formatters;
    }

}