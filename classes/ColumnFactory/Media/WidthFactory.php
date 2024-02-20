<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\Formatter\Suffix;

class WidthFactory extends ColumnFactory
{

    public function get_type(): string
    {
        return 'column-width';
    }

    protected function get_label(): string
    {
        return __('Width', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AttachmentMetaData('width'))
                     ->add(new Suffix('px'));
    }

}