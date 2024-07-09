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

        // TODO remove
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

        $fqn_classes = [
            AC\ColumnFactory\CustomFieldFactory::class,
            AC\ColumnFactory\ActionsFactory::class,
            Post\AttachmentFactory::class,
            Post\AuthorFactory::class,
            Post\FeaturedImageFactory::class,
            Post\FormatsFactory::class,
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
            Post\StickyFactory::class,
            Post\TitleRawFactory::class,
        ];

        foreach ($fqn_classes as $fqn_class) {
            $factories[] = $this->container->make($fqn_class);
        }

        // TODO does this do an additional DB call?
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