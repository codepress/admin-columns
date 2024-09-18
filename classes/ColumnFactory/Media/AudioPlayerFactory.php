<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AudioPlayer;

class AudioPlayerFactory extends BaseColumnFactory
{

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    public function get_column_type(): string
    {
        return 'column-audio_player';
    }

    public function get_label(): string
    {
        return __('Audio Player', 'codepress-admin-columns');
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new AudioPlayer());
    }

}