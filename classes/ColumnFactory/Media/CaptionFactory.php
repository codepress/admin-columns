<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Post\Excerpt;
use AC\FormatterCollection;
use AC\Setting\Config;

class CaptionFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-caption';
    }

    public function get_label(): string
    {
        return __('Caption', 'codepress-admin-columns');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        // TODO add WordLimit setting
        $formatters->add(new Excerpt());

        return $formatters;
    }

}