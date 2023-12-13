<?php

declare(strict_types=1);

namespace AC\Column\Media;

use AC\Settings;

class FileMetaAudio extends FileMeta
{

    public function __construct()
    {
        $this->set_type('column-meta_audio')
             ->set_group('media-audio')
             ->set_label(__('Audio Meta', 'codepress-admin-columns'));
    }

    public function register_settings(): void
    {
        $this->add_setting(new Settings\Column\FileMetaAudio($this));
    }

}