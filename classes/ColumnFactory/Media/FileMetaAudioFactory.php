<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Media\FileMetaAudio;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;

class FileMetaAudioFactory extends BaseColumnFactory
{

    private $audio_meta;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        FileMetaAudio $audio_meta
    ) {
        parent::__construct($base_settings_builder);

        $this->audio_meta = $audio_meta;
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new \AC\Setting\ComponentCollection([
            $this->audio_meta->create($config),
        ]);
    }

    protected function get_settings(Config $config): \AC\Setting\ComponentCollection
    {
        return new ComponentCollection([
            $this->audio_meta->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-meta_audio';
    }

    public function get_label(): string
    {
        return __('Audio Meta', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->prepend(new AttachmentMetaData((string)$config->get('media_meta_key')));

        return $formatters;
    }

}