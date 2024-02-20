<?php

namespace AC\ColumnFactory\Media;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\Media\AttachmentMetaData;
use AC\Setting\Formatter\Suffix;

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

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new AttachmentMetaData('height'))
                     ->add(new Suffix('px'));
    }

}