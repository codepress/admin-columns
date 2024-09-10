<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new AttachmentMetaData('album'));
    }

}