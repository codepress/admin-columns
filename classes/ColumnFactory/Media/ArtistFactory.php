<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\FormatterCollection;

class ArtistFactory extends BaseColumnFactory
{

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    public function get_type(): string
    {
        return 'column-meta_artist';
    }

    protected function get_label(): string
    {
        return __('Artist', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentMetaData('artist'));

        return parent::get_formatters($components, $config, $formatters);
    }

}