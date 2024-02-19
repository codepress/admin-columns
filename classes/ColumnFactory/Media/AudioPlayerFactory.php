<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Media\AudioPlayer;

class AudioPlayerFactory extends ColumnFactory
{

    // Group to group: 'media-audio'

    public function get_type(): string
    {
        return 'column-audio_player';
    }

    protected function get_label(): string
    {
        return __('Audio Player', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AudioPlayer());
    }

}