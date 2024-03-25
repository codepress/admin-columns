<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\Formatter\Suffix;
use AC\Setting\FormatterCollection;

class HeightFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-height';
    }

    protected function get_label(): string
    {
        return __('Height', 'codepress-admin-columns');
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
        $formatters->add(new AttachmentMetaData('height'));
        $formatters->add(new Suffix('px'));

        return parent::get_formatters($components, $config, $formatters);
    }

}