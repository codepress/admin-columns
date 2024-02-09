<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\Builder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\Excerpt;

class ExcerptFactory implements ColumnFactory
{

    public function can_create(string $type): bool
    {
        return 'column-excerpt' === $type;
    }

    public function create(Config $config): Column
    {
        $settings = (new Builder())->set_defaults()
                                   ->set_string_limit()
                                   ->set_before_after()
                                   ->build($config);

        return new Column(
            'column-excerpt',
            __('Excerpt', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Excerpt()),
            $settings
        );
    }

}