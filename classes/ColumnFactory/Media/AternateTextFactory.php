<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\MetaValue;

class AternateTextFactory extends BaseColumnFactory
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

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new MetaValue('_wp_attachment_image_alt'));

        return parent::get_formatters($components, $config, $formatters);
    }

}