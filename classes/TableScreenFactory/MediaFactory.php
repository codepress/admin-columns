<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\Column;
use AC\TableScreen;
use AC\TableScreen\Media;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use WP_Screen;

class MediaFactory implements TableScreenFactory
{

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return 'upload' === $screen->base && 'upload' === $screen->id && 'attachment' === $screen->post_type;
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        return $this->create_table_screen();
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('wp-media'));
    }

    public function create(ListKey $key): TableScreen
    {
        return $this->create_table_screen();
    }

    protected function create_table_screen(): Media
    {
        $columns = [
            Column\CustomField::class,
            Column\Actions::class,
            Column\Post\TitleRaw::class,
            Column\Post\Slug::class,
            Column\Post\TitleRaw::class,
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

        return new Media($columns);
    }

}