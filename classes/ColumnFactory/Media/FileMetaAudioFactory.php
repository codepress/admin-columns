<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\Media\FileMetaAudio;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;

class FileMetaAudioFactory extends BaseColumnFactory
{

    private $audio_meta;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        FileMetaAudio $audio_meta
    ) {
        parent::__construct($component_factory_registry);

        $this->audio_meta = $audio_meta;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->audio_meta);
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new AttachmentMetaData((string)$config->get('media_meta_key')));
    }

}