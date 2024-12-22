<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\ColumnFactory;
use AC\ColumnFactoryDefinitionCollection;
use AC\TableScreen;
use AC\ThirdParty\MediaLibraryAssistant;
use AC\Type\ColumnFactoryDefinition;

class ColumnTypesFactory extends AC\ColumnFactories\BaseFactory
{

    protected function get_factories(TableScreen $table_screen): ColumnFactoryDefinitionCollection
    {
        $collection = new ColumnFactoryDefinitionCollection();

        if ( ! $table_screen instanceof MediaLibraryAssistant\TableScreen) {
            return $collection;
        }

        $factories = [
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
            $collection->add(new ColumnFactoryDefinition($factory));
        }

        return $collection;
    }

}