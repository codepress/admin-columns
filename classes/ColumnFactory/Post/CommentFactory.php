<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings\Column\CommentsFactory;

class CommentFactory implements ColumnFactory
{

    public function can_create(string $type): bool
    {
        return 'column-comment_count' === $type;
    }

    public function create(Config $config): Column
    {
        $settings = (new ComponentCollectionBuilder())->set_defaults()
                                                      ->set(new CommentsFactory())
                                                      ->set_string_limit()
                                                      ->build($config);

        return new Column(
            'column-comment_count',
            __('Comment Count', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings
        );
    }

}