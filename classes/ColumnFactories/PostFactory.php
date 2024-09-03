<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory\Post;
use AC\TableScreen;

final class PostFactory extends BaseFactory
{

    public function get_factories(TableScreen $table_screen): array
    {
        if ( ! $table_screen instanceof AC\PostType) {
            return [];
        }

        $post_type = $table_screen->get_post_type();

        $factories = [
            AC\ColumnFactory\CustomFieldFactory::class,
            AC\ColumnFactory\ActionsFactory::class,
            Post\AttachmentFactory::class,
            Post\AuthorFactory::class,
            Post\IdFactory::class,
            Post\LastModifiedAuthorFactory::class,
            Post\BeforeMoreFactory::class,
            Post\CommentStatusFactory::class,
            Post\ContentFactory::class,
            Post\DatePublishFactory::class,
            Post\DepthFactory::class,
            Post\EstimateReadingTimeFactory::class,
            Post\MenuFactory::class,
            Post\LastModifiedFactory::class,
            Post\OrderFactory::class,
            Post\PageTemplateFactory::class,
            Post\PasswordProtectedFactory::class,
            Post\PathFactory::class,
            Post\PermalinkFactory::class,
            Post\PingStatusFactory::class,
            Post\ParentFactory::class,
            Post\ShortcodesFactory::class,
            Post\ShortLinkFactory::class,
            Post\SlugFactory::class,
            Post\StatusFactory::class,
            Post\WordCountFactory::class,
        ];

        if (post_type_supports($post_type, 'thumbnail')) {
            $factories[] = Post\FeaturedImageFactory::class;
        }

        if (post_type_supports($post_type, 'title')) {
            $factories[] = Post\TitleRawFactory::class;
        }

        if (post_type_supports($post_type, 'post-formats')) {
            $factories[] = Post\FormatsFactory::class;
        }

        // TODO Stefan does this do an additional DB call?
        if (count(ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)) > 0) {
            $factories[] = Post\TaxonomyFactory::class;
        }

        if ('post' === $post_type) {
            $factories[] = Post\StickyFactory::class;
        }

        if (post_type_supports($post_type, 'comments')) {
            $factories[] = Post\CommentCountFactory::class;
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories[] = Post\ExcerptFactory::class;
        }

        return $factories;
    }

}