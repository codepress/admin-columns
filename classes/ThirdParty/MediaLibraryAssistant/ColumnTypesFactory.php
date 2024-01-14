<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Column;
use AC\ColumnTypeCollection;

class ColumnTypesFactory implements AC\ColumnTypesFactory
{

    public function create(AC\TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ($table_screen instanceof TableScreen) {
            return ColumnTypeCollection::from_list([
                Column\CustomField::class,
                Column\Actions::class,
                Column\Post\Slug::class,
                Column\Post\TitleRaw::class,
                Column\Media\Album::class,
                Column\Media\Artist::class,
                Column\Media\Author::class,
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
        }

        return null;
    }

}