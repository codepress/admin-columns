<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Media\FileMetaAudio;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\FormatterCollection;

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

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->audio_meta);
    }

    public function get_type(): string
    {
        return 'column-meta_audio';
    }

    protected function get_label(): string
    {
        return __('Audio Meta', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-audio';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentMetaData((string)$config->get('media_meta_key')));

        return parent::get_formatters($components, $config, $formatters);
    }

}