<?php

namespace AC\ThirdParty\MediaLibraryAssistant\ListScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\ThirdParty\MediaLibraryAssistant\ManageValue;
use AC\ThirdParty\MediaLibraryAssistant\WpListTableFactory;
use AC\Type\QueryAware;
use AC\Type\Url;
use MLACore;

class MediaLibrary extends AC\ListScreenPost
{

    public function __construct()
    {
        parent::__construct('attachment');

        $this->set_key('mla-media-assistant')
             ->set_label(__('Media Library Assistant', 'codepress-admin-columns'))
             ->set_singular_label(__('Assistant', 'codepress-admin-columns'))
             ->set_screen_id('media_page_' . MLACore::ADMIN_PAGE_SLUG)
             ->set_group('media');
    }

    public function get_table_url(): QueryAware
    {
        return new Url\ListTable\Media(
            $this->has_id() ? $this->get_id() : null,
            MLACore::ADMIN_PAGE_SLUG
        );
    }

    public function manage_value(): AC\Table\ManageValue
    {
        return new ManageValue(new ColumnRepository($this));
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ThirdParty\MediaLibraryAssistant\ListTable((new WpListTableFactory())->create());
    }

    public function register_column_types(): void
    {
        parent::register_column_types();

        $this->register_column_types_from_list([
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

}