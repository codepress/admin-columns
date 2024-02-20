<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Media\NestedAttachmentMetaData;
use AC\Settings\Column\FileMetaVideoFormatFactory;

class FileMetaVideoFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        FileMetaVideoFormatFactory $video_format_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($video_format_factory);
    }

    public function get_type(): string
    {
        return 'column-meta_video';
    }

    protected function get_label(): string
    {
        return __('Video Meta', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-video';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        $meta_keys = array_filter(array_map('trim', explode('.', (string)$config->get('media_meta_key'))));

        return parent::create_formatter_builder($components, $config)
                     ->prepend(new NestedAttachmentMetaData($meta_keys));
    }

}