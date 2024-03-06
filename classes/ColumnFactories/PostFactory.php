<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\CustomField;
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

        $factories[] = $this->container->get(Post\IdFactory::class);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;

        $post_type = $table_screen->get_post_type();
        $meta_type = new MetaType(MetaType::POST);
        $meta_key_factory = $this->container->make(
            Settings\Column\MetaKeyFactory::class,
            [
                'meta_type' => $meta_type,
            ]
        );

        $this->container->set(AC\Type\PostTypeSlug::class, new AC\Type\PostTypeSlug($post_type));

        // TODO Test
        $factories[] = $this->container->make(CustomField\TextFactory::class, [
            'meta_key_factory' => $meta_key_factory,
        ]);
        $factories[] = $this->container->make(CustomField\NumberFactory::class, [
            'meta_key_factory' => $meta_key_factory,
        ]);

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
        $factories[] = $this->container->get(Post\MenuFactory::class);
        $factories[] = $this->container->get(Post\LastModifiedFactory::class);
        $factories[] = $this->container->get(Post\OrderFactory::class);
        $factories[] = $this->container->get(Post\PageTemplateFactory::class);
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

        if (count(ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)) > 0) {
            $factories[] = $this->container->make(Post\TaxonomyFactory::class, [
                'taxonomy_factory' => new Settings\Column\TaxonomyFactory($post_type),
                'term_link_factory' => new Settings\Column\TermLinkFactory($post_type),
            ]);
        }

        $factories[] = $this->container->get(Post\WordCountFactory::class);

        if (post_type_supports($post_type, 'comments')) {
            $factories[] = $this->container->get(CommentFactory::class);
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories[] = $this->container->get(ExcerptFactory::class);
        }

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}