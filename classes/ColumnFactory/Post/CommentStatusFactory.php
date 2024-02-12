<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\HasCommentStatus;

class CommentStatusFactory implements ColumnFactory
{

    private $builder;

    public function __construct(ComponentCollectionBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->build($config);

        return new Column(
            'column-comment_status',
            __('Allow Comments', 'codepress-admin-columns'),
            new HasCommentStatus('open'),
            $settings
        );
    }

}