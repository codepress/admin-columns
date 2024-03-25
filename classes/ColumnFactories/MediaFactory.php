<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\Collection;
use AC\ColumnFactories;
use AC\ColumnFactory\Media;
use AC\TableScreen;
use AC\Vendor\DI\Container;

class MediaFactory implements ColumnFactories
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories
    {
        if ( ! $table_screen instanceof AC\TableScreen\Media) {
            return null;
        }


        // Todo IMPLEMENT - Preview Modal for Preview
        $factoryClasses = [
            Media\AlbumFactory::class,
            Media\ArtistFactory::class,
            Media\AudioPlayerFactory::class,
            Media\AvailableSizesFactory::class,
            Media\CaptionFactory::class,
            Media\DescriptionFactory::class,
            Media\DimensionsFactory::class,
            Media\DownloadFactory::class,
            Media\ExifDataFactory::class,
            Media\FileMetaAudioFactory::class,
            Media\FileMetaVideoFactory::class,
            Media\FileNameFactory::class,
            Media\FileSizeFactory::class,
            Media\FullPathFactory::class,
            Media\HeightFactory::class,
            Media\ImageFactory::class,
            Media\MimeTypeFactory::class,
//            Media\PreviewFactory::class,
            Media\VideoPlayerFactory::class,
            Media\WidthFactory::class
        ];

        foreach ($factoryClasses as $factoryClass) {
            $factories[$factoryClass] = $this->container->make($factoryClass);
        }

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}