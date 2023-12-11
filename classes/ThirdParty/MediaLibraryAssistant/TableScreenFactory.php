<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Column;
use AC\Type\ListKey;
use MLACore;
use WP_Screen;

class TableScreenFactory implements AC\TableScreenFactory
{

    public function create(ListKey $key): AC\TableScreen
    {
        return new TableScreen(
            $this->get_columns()
        );
    }

    protected function get_columns(): array
    {
        return [
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
        ];
    }

    public function can_create(ListKey $key): bool
    {
        return $key->equals(new ListKey('mla-media-assistant'));
    }

    public function create_from_wp_screen(WP_Screen $screen): AC\TableScreen
    {
        return new TableScreen(
            $this->get_columns()
        );
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        return class_exists('MLACore') && 'media_page_' . MLACore::ADMIN_PAGE_SLUG === $screen->id;
    }

}