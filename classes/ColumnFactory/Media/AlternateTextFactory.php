<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\Meta;
use AC\FormatterCollection;
use AC\Setting\Config;

class AlternateTextFactory extends BaseColumnFactory
{

    protected function get_group(): ?string
    {
        return 'media-image';
    }

    public function get_column_type(): string
    {
        return 'column-alternate_text';
    }

    public function get_label(): string
    {
        return __('Alternative Text', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new Meta('_wp_attachment_image_alt'));

        return $formatters;
    }

}