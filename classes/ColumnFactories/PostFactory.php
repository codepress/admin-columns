<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\CustomFieldFactory;
use AC\ColumnFactory\Post;
use AC\ColumnFactory\Post\CommentFactory;
use AC\ColumnFactory\Post\ExcerptFactory;
use AC\MetaType;
use AC\Settings;
use AC\Settings\Column\CustomFieldTypeFactory;
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

        if (post_type_supports($post_type, 'comments')) {
            $factories[] = $this->container->get(CommentFactory::class);
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories[] = $this->container->get(ExcerptFactory::class);
        }

        $factories[] = $this->container->make(
            CustomFieldFactory::class,
            [
                'custom_field_factory' => new Settings\Column\CustomFieldFactory(
                    new MetaType(MetaType::POST),
                    $this->container->get(CustomFieldTypeFactory::class)
                ),
            ]
        );
        $factories[] = $this->container->get(Post\AttachmentFactory::class);
        $factories[] = $this->container->get(Post\AuthorFactory::class);
        $factories[] = $this->container->get(Post\FeaturedImageFactory::class);
        $factories[] = $this->container->get(Post\FormatsFactory::class);
        $factories[] = $this->container->get(Post\IdFactory::class);
        $factories[] = $this->container->get(Post\LastModifiedAuthorFactory::class);
        $factories[] = $this->container->get(Post\BeforeMoreFactory::class);
        $factories[] = $this->container->get(Post\CommentStatusFactory::class);
        $factories[] = $this->container->get(Post\ContentFactory::class);
        $factories[] = $this->container->get(Post\DatePublishFactory::class);
        $factories[] = $this->container->get(Post\DepthFactory::class);
        $factories[] = $this->container->get(Post\EstimateReadingTimeFactory::class);
        $factories[] = $this->container->make(Post\MenuFactory::class, [
            'post_type' => $table_screen->get_post_type(),
        ]);
        $factories[] = $this->container->get(Post\LastModifiedFactory::class);
        $factories[] = $this->container->get(Post\OrderFactory::class);
        $factories[] = $this->container->make(Post\PageTemplateFactory::class, [
            'post_type' => $table_screen->get_post_type(),
        ]);
        $factories[] = $this->container->get(Post\PasswordProtectedFactory::class);
        $factories[] = $this->container->get(Post\PathFactory::class);
        $factories[] = $this->container->get(Post\PermalinkFactory::class);
        $factories[] = $this->container->get(Post\PingStatusFactory::class);
        $factories[] = $this->container->get(Post\ParentFactory::class);
        $factories[] = $this->container->get(Post\ShortcodesFactory::class);
        $factories[] = $this->container->get(Post\ShortLinkFactory::class);
        $factories[] = $this->container->get(Post\SlugFactory::class);
        $factories[] = $this->container->get(Post\StatusFactory::class);
        $factories[] = $this->container->get(Post\StickyFactory::class);
        $factories[] = $this->container->get(Post\TitleRawFactory::class);
        $factories[] = $this->container->get(Post\WordCountFactory::class);

        $_factories = [];
        foreach ($factories as $factory) {
            $_factories[$factory->get_type()] = $factory;
        }

        return new Collection\ColumnFactories($_factories);
    }

}