<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Collection;
use AC\Collection\ColumnFactories;
use AC\ColumnFactory;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;
use AC\Vendor\DI\Container;

class ColumnTypesFactory implements AC\ColumnFactoryCollectionFactory
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ColumnFactories
    {
        $collection = new Collection\ColumnFactories();

        if ( ! $table_screen instanceof MediaLibraryAssistant\TableScreen) {
            return $collection;
        }

        //TODO add Actionfactory
        //TODO add MenuFactory
        $factories[] = $this->container->make(ColumnFactory\Post\SlugFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Post\TitleRawFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Post\AuthorFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\AlbumFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\ArtistFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\AvailableSizesFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\DimensionsFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\ExifDataFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\FileMetaAudioFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\FileMetaVideoFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\FileSizeFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\HeightFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\ImageFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\PreviewFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\VideoPlayerFactory::class);
        $factories[] = $this->container->make(ColumnFactory\Media\WidthFactory::class);

        foreach ($factories as $factory) {
            $collection->add($factory->get_column_type(), $factory);
        }

        return $collection;
    }

}