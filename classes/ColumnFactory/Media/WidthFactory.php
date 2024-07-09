<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Media\AttachmentMetaData;
use AC\Value\Formatter\Suffix;

class WidthFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-width';
    }

    public function get_label(): string
    {
        return __('Width', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AttachmentMetaData('width'));
        $formatters->add(new Suffix('px'));

        return parent::get_formatters($components, $config, $formatters);
    }
}