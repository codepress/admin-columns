<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Collection\ColumnFactories;
use AC\ColumnFactory;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;
use AC\Vendor\DI\Container;

class ColumnTypesFactory implements AC\ColumnFactoryCollectionFactory
{

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ColumnFactories
    {
        $collection = new ColumnFactories();

        if ( ! $table_screen instanceof MediaLibraryAssistant\TableScreen) {
            return $collection;
        }

        $factories = [

            //TODO add ActionFactory
            //TODO add MenuFactory
            ColumnFactory\Post\SlugFactory::class,
            ColumnFactory\Post\TitleRawFactory::class,
            ColumnFactory\Post\AuthorFactory::class,
            ColumnFactory\Media\AlbumFactory::class,
            ColumnFactory\Media\ArtistFactory::class,
            ColumnFactory\Media\AvailableSizesFactory::class,
            ColumnFactory\Media\DimensionsFactory::class,
            ColumnFactory\Media\ExifDataFactory::class,
            ColumnFactory\Media\FileMetaAudioFactory::class,
            ColumnFactory\Media\FileMetaVideoFactory::class,
            ColumnFactory\Media\FileSizeFactory::class,
            ColumnFactory\Media\HeightFactory::class,
            ColumnFactory\Media\ImageFactory::class,
            ColumnFactory\Media\PreviewFactory::class,
            ColumnFactory\Media\VideoPlayerFactory::class,
            ColumnFactory\Media\WidthFactory::class,

        ];

        foreach ($factories as $factory) {
            $collection->add($this->container->make($factory));
        }

        return $collection;
    }

}