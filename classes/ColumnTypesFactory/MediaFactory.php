<?php

declare(strict_types=1);

namespace AC\ColumnTypesFactory;

use AC;
use AC\Column;
use AC\ColumnTypeCollection;
use AC\TableScreen;
use AC\Type\ListKey;

class MediaFactory implements AC\ColumnTypesFactory
{

    use ColumnTypesTrait;

    public function create(TableScreen $table_screen): ?ColumnTypeCollection
    {
        if ( ! $table_screen->get_key()->equals(new ListKey('wp-media'))) {
            return null;
        }

        return $this->create_from_list($this->get_columns());
    }

    private function get_columns(): array
    {
        $columns = [
            Column\Media\Album::class,
            Column\Media\AlternateText::class,
            Column\Media\Artist::class,
            Column\Media\Author::class,
            Column\Media\AuthorName::class,
            Column\Media\AvailableSizes::class,
            Column\Media\Caption::class,
            Column\Media\Date::class,
            Column\Media\Description::class,
            Column\Media\Dimensions::class,
            Column\Media\FileMetaAudio::class,
            Column\Media\FileMetaVideo::class,
            Column\Media\FileName::class,
            Column\Media\FileSize::class,
            Column\Media\FullPath::class,
            Column\Media\Height::class,
            Column\Media\ID::class,
            Column\Media\Image::class,
            Column\Media\MediaParent::class,
            Column\Media\Menu::class,
            Column\Media\MimeType::class,
            Column\Media\Preview::class,
            Column\Media\Taxonomy::class,
            Column\Media\Title::class,
            Column\Media\VideoPlayer::class,
            Column\Media\Width::class,
        ];

        if (post_type_supports('attachment', 'comments')) {
            $columns[] = Column\Media\Comments::class;
        }
        if (function_exists('exif_read_data')) {
            $columns[] = Column\Media\ExifData::class;
        }

        return $columns;
    }

}