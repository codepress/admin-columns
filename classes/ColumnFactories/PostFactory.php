<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Post\ExcerptFactory;
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

        // TODO use ComponentCollectionBuilderFactory? because it will be loaded static now, resulting in duplicate settings (label, width etc.)
        //        $factories['column-attachment'] = $this->container->get(AttachmentFactory::class);
        //        $factories['column-author_name'] = $this->container->get(AuthorFactory::class);
        //
        //        if (post_type_supports($post_type, 'comments')) {
        //            $factories['column-comment_count'] = $this->container->get(CommentFactory::class);
        //        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories[] = $this->container->get(ExcerptFactory::class);
        }

        //        $factories['column-meta'] = $this->container->make(CustomFieldFactory::class, [
        //            'meta_type' => new MetaType(MetaType::POST),
        //        ]);
        //        $factories['column-featured_image'] = $this->container->get(Post\FeaturedImageFactory::class);
        //        $factories['column-post_formats'] = $this->container->get(Post\FormatsFactory::class);
        //        $factories['column-postid'] = $this->container->get(Post\IdFactory::class);
        //        $factories['column-last_modified_author'] = $this->container->get(
        //            Post\LastModifiedAuthorFactory::class
        //        );
        //        $factories['column-before_moretag'] = $this->container->get(Post\BeforeMoreFactory::class);
        //        $factories['column-comment_status'] = $this->container->get(Post\CommentStatusFactory::class);
        //        $factories['column-content'] = $this->container->get(Post\ContentFactory::class);
        //        $factories['column-date_published'] = $this->container->get(Post\DatePublishFactory::class);
        //        $factories['column-depth'] = $this->container->get(Post\DepthFactory::class);
        //        $factories['column-estimated_reading_time'] = $this->container->get(
        //            Post\EstimateReadingTimeFactory::class
        //        );
        //        $factories['column-used_by_menu'] = $this->container->make(Post\MenuFactory::class, [
        //            'post_type' => $table_screen->get_post_type(),
        //        ]);
        //        $factories['column-modified'] = $this->container->get(Post\LastModifiedFactory::class);
        //        $factories['column-order'] = $this->container->get(Post\OrderFactory::class);
        //        $factories['column-page_template'] = $this->container->make(Post\PageTemplateFactory::class, [
        //            'post_type' => $table_screen->get_post_type(),
        //        ]);
        //        $factories['column-password_protected'] = $this->container->get(Post\PasswordProtectedFactory::class);
        //        $factories['column-path'] = $this->container->get(Post\PathFactory::class);
        //        $factories['column-permalink'] = $this->container->get(Post\PermalinkFactory::class);
        //        $factories['column-ping_status'] = $this->container->get(Post\PingStatusFactory::class);
        //        $factories['column-parent'] = $this->container->get(Post\ParentFactory::class);
        //        $factories['column-shortcode'] = $this->container->get(Post\ShortcodesFactory::class);
        //        $factories['column-shortlink'] = $this->container->get(Post\ShortLinkFactory::class);
        //        $factories['column-slug'] = $this->container->get(Post\SlugFactory::class);
        //        $factories['column-status'] = $this->container->get(Post\StatusFactory::class);

        $_factories = [];
        foreach ($factories as $factory) {
            $_factories[$factory->get_type()] = $factory;
        }

        return new Collection\ColumnFactories($_factories);
    }

}