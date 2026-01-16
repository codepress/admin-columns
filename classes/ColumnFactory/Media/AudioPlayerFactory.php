<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Media\AudioPlayer;
use AC\FormatterCollection;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new AudioPlayer());

        return $formatters;
    }

}