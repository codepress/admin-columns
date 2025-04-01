<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\MetaValue;

class AlternateTextFactory extends ColumnFactory
{

    // Group to group: 'media-audio'

    public function get_column_type(): string
    {
        return 'media-image';
    }

    public function get_label(): string
    {
        return __('Alternative Text', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new MetaValue('_wp_attachment_image_alt'));

        return $formatters;
    }

}