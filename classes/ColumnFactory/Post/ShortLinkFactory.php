<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Formatter\Linkable;
use AC\Setting\Formatter\Post\ShortLink;
use AC\Setting\FormatterCollection;

class ShortLinkFactory extends BaseColumnFactory
{

    protected function get_label(): string
    {
        return __('Shortlink', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-shortlink';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new ShortLink());
        $formatters->add(new Linkable());

        return parent::get_formatters($components, $config, $formatters);
    }

}