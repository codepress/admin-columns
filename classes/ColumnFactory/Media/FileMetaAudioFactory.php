<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Settings\Column\FileMetaAudioFormatFactory;

class FileMetaAudioFactory extends ColumnFactory
{

    // Group to group: 'media-audio'

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        FileMetaAudioFormatFactory $audio_format_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($audio_format_factory);
    }

    // TODO 

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

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AttachmentMetaData((string)$config->get('media_meta_key')));
    }

}