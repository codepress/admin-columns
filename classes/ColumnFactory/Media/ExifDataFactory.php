<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\ExifData;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class ExifDataFactory extends ColumnFactory
{

    private $exif_data;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ExifData $exif_data
    ) {
        parent::__construct($component_factory_registry);

        $this->exif_data = $exif_data;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->exif_data);
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    public function get_type(): string
    {
        return 'column-exif_data';
    }

    protected function get_label(): string
    {
        return __('Image Meta (EXIF)', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\Media\AttachmentMetaData('image_meta'));
        $formatters->add(new Formatter\Media\ExifData((string)$config->get('exif_data')));

        return parent::get_formatters($components, $config, $formatters);
    }

}