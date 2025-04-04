<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Linkable;
use AC\Value\Formatter\Post\ShortLink;

class ShortLinkFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Shortlink', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-shortlink';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        $formatters->add(new ShortLink());
        $formatters->add(new Linkable());

        return $formatters;
    }

}