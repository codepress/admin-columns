<?php

namespace AC\ColumnFactory\Media;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\ExifData;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class ExifDataFactory extends BaseColumnFactory
{

    private ExifData $exif_data;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ExifData $exif_data
    ) {
        parent::__construct($default_settings_builder);

        $this->exif_data = $exif_data;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->exif_data->create($config),
        ]);
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    public function get_column_type(): string
    {
        return 'column-exif_data';
    }

    public function get_label(): string
    {
        return __('Image Meta (EXIF)', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new AC\Formatter\Media\AttachmentMetaData('image_meta'),
            new AC\Formatter\Media\ExifData((string)$config->get('exif_data')),
        ]);

        return $formatters->merge(parent::get_formatters($config));
    }

}