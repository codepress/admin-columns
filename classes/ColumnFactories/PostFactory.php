<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Post;
use AC\MetaType;
use AC\Relation;
use AC\TableScreen;
use AC\Type\ListKey;
use AC\Type\PostTypeSlug;
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

        $this->container->set(PostTypeSlug::class, new PostTypeSlug($post_type));
        $this->container->set(Relation::class, new AC\Relation\Post($post_type));
        $this->container->set(MetaType::class, MetaType::create_post_type());
        $this->container->set(ListKey::class, $table_screen->get_key());

        //        $meta_type = new MetaType(MetaType::POST);
        //
        //        $meta_key_factory = $this->container->make(
        //            Settings\Column\MetaKeyFactory::class,
        //            [
        //                'meta_type' => $meta_type,
        //            ]
        //        );
        //
        //        // TODO Test
        //        $factories[] = $this->container->make(CustomField\TextFactory::class, [
        //            'meta_key_factory' => $meta_key_factory,
        //        ]);
        //        $factories[] = $this->container->make(CustomField\NumberFactory::class, [
        //            'meta_key_factory' => $meta_key_factory,
        //        ]);
        //
        //        $factories[] = $this->container->make(
        //            CustomFieldFactory::class,
        //            [
        //                'custom_field_factory' => new Settings\Column\CustomFieldFactory(
        //                    new MetaType(MetaType::POST),
        //                    $this->container->get(CustomFieldTypeFactory::class)
        //                ),
        //            ]
        //        );

        $factories[] = $this->container->make(AC\ColumnFactory\CustomFieldFactory::class);

        $factories[] = $this->container->make(Post\AttachmentFactory::class);
        $factories[] = $this->container->make(Post\AuthorFactory::class);
        $factories[] = $this->container->make(Post\FeaturedImageFactory::class);
        $factories[] = $this->container->make(Post\FormatsFactory::class);
        $factories[] = $this->container->make(Post\IdFactory::class);
        $factories[] = $this->container->make(Post\LastModifiedAuthorFactory::class);
        $factories[] = $this->container->make(Post\BeforeMoreFactory::class);
        $factories[] = $this->container->make(Post\CommentStatusFactory::class);
        $factories[] = $this->container->make(Post\ContentFactory::class);
        $factories[] = $this->container->make(Post\DatePublishFactory::class);
        $factories[] = $this->container->make(Post\DepthFactory::class);
        $factories[] = $this->container->make(Post\EstimateReadingTimeFactory::class);
        $factories[] = $this->container->make(Post\MenuFactory::class);
        $factories[] = $this->container->make(Post\LastModifiedFactory::class);
        $factories[] = $this->container->make(Post\OrderFactory::class);
        $factories[] = $this->container->make(Post\PageTemplateFactory::class);
        $factories[] = $this->container->make(Post\PasswordProtectedFactory::class);
        $factories[] = $this->container->make(Post\PathFactory::class);
        $factories[] = $this->container->make(Post\PermalinkFactory::class);
        $factories[] = $this->container->make(Post\PingStatusFactory::class);
        $factories[] = $this->container->make(Post\ParentFactory::class);
        $factories[] = $this->container->make(Post\ShortcodesFactory::class);
        $factories[] = $this->container->make(Post\ShortLinkFactory::class);
        $factories[] = $this->container->make(Post\SlugFactory::class);
        $factories[] = $this->container->make(Post\StatusFactory::class);
        $factories[] = $this->container->make(Post\StickyFactory::class);
        $factories[] = $this->container->make(Post\TitleRawFactory::class);

        if (count(ac_helper()->taxonomy->get_taxonomy_selection_options($post_type)) > 0) {
            $factories[] = $this->container->make(Post\TaxonomyFactory::class);
        }

        $factories[] = $this->container->make(Post\WordCountFactory::class);

        if (post_type_supports($post_type, 'comments')) {
            $factories[] = $this->container->make(Post\CommentCountFactory::class);
        }

        if (post_type_supports($post_type, 'excerpt')) {
            $factories[] = $this->container->make(Post\ExcerptFactory::class);
        }

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_column_type(), $factory);
        }

        return $collection;
    }

}