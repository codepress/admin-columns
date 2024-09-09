<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory\Post;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;
use AC\Type\ColumnFactoryDefinition;
use AC\Type\TableScreenContext;

final class PostFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        if ( ! $table_screen instanceof AC\PostType) {
            return $collection;
        }

        $table_screen_context = TableScreenContext::from_table_screen($table_screen);

        if ( ! $table_screen_context) {
            return $collection;
        }

        $collection->add(
            new ColumnFactoryDefinition(
                AC\ColumnFactory\CustomFieldFactory::class,
                [
                    'table_screen_context' => $table_screen_context,
                ]
            )
        );

        $factories = [
            AC\ColumnFactory\ActionsFactory::class,
            Post\AttachmentFactory::class,
            Post\AuthorFactory::class,
        ];
        //            Post\IdFactory::class,
        //            Post\LastModifiedAuthorFactory::class,
        //            Post\BeforeMoreFactory::class,
        //            Post\CommentStatusFactory::class,
        //            Post\ContentFactory::class,
        //            Post\DatePublishFactory::class,
        //            Post\DepthFactory::class,
        //            Post\EstimateReadingTimeFactory::class,
        //            Post\MenuFactory::class,
        //            Post\LastModifiedFactory::class,
        //            Post\OrderFactory::class,
        //            Post\PageTemplateFactory::class,
        //            Post\PasswordProtectedFactory::class,
        //            Post\PathFactory::class,
        //            Post\PermalinkFactory::class,
        //            Post\PingStatusFactory::class,
        //            Post\ParentFactory::class,
        //            Post\ShortcodesFactory::class,
        //            Post\ShortLinkFactory::class,
        //            Post\SlugFactory::class,
        //            Post\StatusFactory::class,
        //            Post\WordCountFactory::class,
        //        ];
        //
        //        $post_type = (string)$table_screen->get_post_type();
        //
        //        if (post_type_supports($post_type, 'thumbnail')) {
        //            $factories[] = Post\FeaturedImageFactory::class;
        //        }
        //
        //        if (post_type_supports($post_type, 'title')) {
        //            $factories[] = Post\TitleRawFactory::class;
        //        }
        //
        //        if (post_type_supports($post_type, 'post-formats')) {
        //            $factories[] = Post\FormatsFactory::class;
        //        }
        //
        //        if (count(ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)) > 0) {
        //            $factories[] = Post\TaxonomyFactory::class;
        //        }
        //
        //        if ('post' === $post_type) {
        //            $factories[] = Post\StickyFactory::class;
        //        }
        //
        //        if (post_type_supports($post_type, 'comments')) {
        //            $factories[] = Post\CommentCountFactory::class;
        //        }
        //
        //        if (post_type_supports($post_type, 'excerpt')) {
        //            $factories[] = Post\ExcerptFactory::class;
        //        }

        foreach ($factories as $factory) {
            $collection->add(new ColumnFactoryDefinition($factory));
        }

        return $collection;
    }

}