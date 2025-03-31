<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\ExifData;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class ExifDataFactory extends BaseColumnFactory
{

    private $exif_data;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ExifData $exif_data
    ) {
        parent::__construct($base_settings_builder);

        $this->exif_data = $exif_data;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->exif_data);
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\Media\ExifData((string)$config->get('exif_data')));
        $formatters->prepend(new Formatter\Media\AttachmentMetaData('image_meta'));
    }

}