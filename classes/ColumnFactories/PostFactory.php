<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\CustomFieldFactory;
use AC\ColumnFactory\Post\AttachmentFactory;
use AC\ColumnFactory\Post\AuthorFactory;
use AC\ColumnFactory\Post\CommentFactory;
use AC\ColumnFactory\Post\ExcerptFactory;
use AC\MetaType;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class PostFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return null;
        }

        $post_type = $table_screen->get_post_type();

        $factories['column-attachment'] = new AttachmentFactory();
        $factories['column-author_name'] = new AuthorFactory();

        if (post_type_supports($post_type, 'comments')) {
            $factories['column-comment_count'] = new CommentFactory();
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories['column-excerpt'] = new ExcerptFactory();
        }

        $factories['column-meta'] = new CustomFieldFactory(new MetaType(MetaType::POST), $this->container);

        return new Collection\ColumnFactories($factories);

        //        $factories = [
        //            new AttachmentFactory(),
        //            new AuthorFactory(),
        //        ];
        //
        //        if (post_type_supports($post_type, 'comments')) {
        //            $factories[] = new CommentFactory();
        //        }
        //
        //        if (post_type_supports($post_type, 'excerpt')) {
        //            $factories[] = new ExcerptFactory();
        //        }
        //
        //        $factories[] = new CustomFieldFactory(new MetaType(MetaType::POST), $this->container);
        //
        //        return new Collection\ColumnFactories(
        //            $factories
        //        );
    }

}