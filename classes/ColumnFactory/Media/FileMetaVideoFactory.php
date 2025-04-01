<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\Media\FileMetaVideo;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\NestedAttachmentMetaData;

class FileMetaVideoFactory extends ColumnFactory
{

    private $file_meta_video;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        FileMetaVideo $file_meta_video
    ) {
        parent::__construct($base_settings_builder);

        $this->file_meta_video = $file_meta_video;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->file_meta_video->create($config),
        ]);
    }

    public function get_column_type(): string
    {
        return 'column-meta_video';
    }

    public function get_label(): string
    {
        return __('Video Meta', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-video';
    }

    //TODO TEST
    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $meta_keys = array_filter(array_map('trim', explode('.', $config->get('media_meta_key', ''))));

        $formatters->add(new NestedAttachmentMetaData($meta_keys));

        return $formatters;
    }

}