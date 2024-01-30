<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Column;
use AC\Column\CustomField;
use AC\ColumnTypeCollection;
use AC\MetaType;

class ColumnTypesFactory implements AC\ColumnTypesFactory
{

    public function create(AC\TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ($table_screen instanceof TableScreen) {
            $collection = ColumnTypeCollection::from_list([
                Column\Actions::class,
                Column\Post\Slug::class,
                Column\Post\TitleRaw::class,
                Column\Media\Album::class,
                Column\Media\Artist::class,
                Column\Post\Author::class,
                Column\Media\AvailableSizes::class,
                Column\Media\Date::class,
                Column\Media\Dimensions::class,
                Column\Media\ExifData::class,
                Column\Media\FileMetaAudio::class,
                Column\Media\FileMetaVideo::class,
                Column\Media\FileSize::class,
                Column\Media\Height::class,
                Column\Media\Image::class,
                Column\Media\Menu::class,
                Column\Media\Preview::class,
                Column\Media\Title::class,
                Column\Media\VideoPlayer::class,
                Column\Media\Width::class,
            ]);

            $collection->add(new CustomField(new MetaType(MetaType::POST)));
        }

        return null;
    }

}