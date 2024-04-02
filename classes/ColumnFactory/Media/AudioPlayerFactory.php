<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AudioPlayer;
use AC\Setting\FormatterCollection;

class AudioPlayerFactory extends BaseColumnFactory
{

    // Group to group: 'media-audio'

    public function get_column_type(): string
    {
        return 'column-audio_player';
    }

    protected function get_label(): string
    {
        return __('Audio Player', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AudioPlayer());

        return parent::get_formatters($components, $config, $formatters);
    }

}