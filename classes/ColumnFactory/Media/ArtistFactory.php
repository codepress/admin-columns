<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;

class ArtistFactory extends ColumnFactory
{

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    public function get_column_type(): string
    {
        return 'column-meta_artist';
    }

    public function get_label(): string
    {
        return __('Artist', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AttachmentMetaData('artist'));

        return $formatters;
    }

}