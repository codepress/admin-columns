<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\Author;

class AuthorFactory implements ColumnFactory
{

    public function can_create(string $type): bool
    {
        return 'column-author_name' === $type;
    }

    public function create(Config $config): Column
    {
        $settings = (new ComponentCollectionBuilder())->set_defaults()
                                                      ->set_user()
                                                      ->build($config);

        return new Column(
            'column-author_name',
            __('Author', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new Author()),
            $settings
        );
    }

}