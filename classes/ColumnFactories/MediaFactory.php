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

        $factories[] = $this->container->get(Media\AlbumFactory::class);
        $factories[] = $this->container->get(Media\AternateTextFactory::class);
        $factories[] = $this->container->get(Media\ArtistFactory::class);
        $factories[] = $this->container->get(Media\AudioPlayerFactory::class);
        $factories[] = $this->container->get(Media\AvailableSizesFactory::class);
        $factories[] = $this->container->get(Media\CaptionFactory::class);
        $factories[] = $this->container->get(Media\DescriptionFactory::class);
        //        $factories[] = $this->container->get(Media\DimensionsFactory::class);
        //        $factories[] = $this->container->get(Media\DownloadFactory::class);
        //        $factories[] = $this->container->get(Media\ExifDataFactory::class);
        //        $factories[] = $this->container->get(Media\FileMetaAudioFactory::class);
        //        $factories[] = $this->container->get(Media\FileMetaVideoFactory::class);
        //        $factories[] = $this->container->get(Media\FileNameFactory::class);
        //        $factories[] = $this->container->get(Media\FileSizeFactory::class);
        //        $factories[] = $this->container->get(Media\FullPathFactory::class);
        //        $factories[] = $this->container->get(Media\HeightFactory::class);
        //        $factories[] = $this->container->get(Media\ImageFactory::class);
        //        $factories[] = $this->container->get(Media\MimeTypeFactory::class);
        //        $factories[] = $this->container->get(Media\PreviewFactory::class);
        //        $factories[] = $this->container->get(Media\VideoPlayerFactory::class);
        //        $factories[] = $this->container->get(Media\WidthFactory::class);

        //        $factories[] = $this->container->make(Media\ExifData::class, [
        //            'exif_data_factory' => new AC\Settings\Column\ExifDataFactory(),
        //        ]);

        //        $factories[] = $this->container->make(Post\MenuFactory::class, [
        //            'post_type' => $table_screen->get_post_type(),
        //        ]);

        $collection = new Collection\ColumnFactories();

        foreach ($factories as $factory) {
            $collection->add($factory->get_type(), $factory);
        }

        return $collection;
    }

}