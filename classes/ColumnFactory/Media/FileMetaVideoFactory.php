<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Media\FileMetaVideo;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\NestedAttachmentMetaData;
use AC\Setting\FormatterCollection;

class FileMetaVideoFactory extends BaseColumnFactory
{

    private $file_meta_video;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        FileMetaVideo $file_meta_video
    ) {
        parent::__construct($component_factory_registry);

        $this->file_meta_video = $file_meta_video;
    }

    protected function add_component_factories(Config $config): void
    {
        $this->add_component_factory($this->file_meta_video);

        parent::add_component_factories($config);
    }

    public function get_column_type(): string
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

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $meta_keys = array_filter(array_map('trim', explode('.', (string)$config->get('media_meta_key'))));

        $formatters->add(new NestedAttachmentMetaData($meta_keys));

        return parent::get_formatters($components, $config, $formatters);
    }
}