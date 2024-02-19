<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Media\AttachmentMetaData;

class ArtistFactory extends ColumnFactory
{

    // Group to group: 'media-audio'

    public function get_type(): string
    {
        return 'column-meta_artist';
    }

    protected function get_label(): string
    {
        return __('Artist', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AttachmentMetaData('artist'));
    }

}