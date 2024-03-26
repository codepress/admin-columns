<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\FormatterCollection;

class AlbumFactory extends BaseColumnFactory
{

    // Group to group: 'media-audio'

    public function get_type(): string
    {
        return 'column-meta_album';
    }

    protected function get_label(): string
    {
        return __('Album', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentMetaData('album'));

        return parent::get_formatters($components, $config, $formatters);
    }

}