<?php

declare(strict_types=1);

namespace AC\ColumnFactories;

use AC;
use AC\ColumnFactory\Media;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;

class MediaFactory extends BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        $factories = [
            Media\AlbumFactory::class,
            Media\ArtistFactory::class,
            Media\AudioPlayerFactory::class,
            Media\AvailableSizesFactory::class,
            Media\CaptionFactory::class,
            Media\DimensionsFactory::class,
            Media\DownloadFactory::class,
            Media\FileMetaAudioFactory::class,
            Media\FileMetaVideoFactory::class,
            Media\FileNameFactory::class,
            Media\FileSizeFactory::class,
            Media\FullPathFactory::class,
            Media\HeightFactory::class,
            Media\ImageFactory::class,
            Media\MimeTypeFactory::class,
            Media\PreviewFactory::class,
            Media\VideoPlayerFactory::class,
            Media\WidthFactory::class,
        ];

        if (function_exists('exif_read_data')) {
            $factories[] = Media\ExifDataFactory::class;
        }

        foreach ($factories as $factory) {
            $collection->add(
                new AC\Type\ColumnFactoryDefinition($factory)
            );
        }

        return $collection;
    }

}