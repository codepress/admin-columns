<?php

namespace AC\ThirdParty\MediaLibraryAssistant\ListScreen;

use AC;
use AC\Column;
use AC\ColumnRepository;
use AC\MetaType;
use AC\ThirdParty\MediaLibraryAssistant\ManageValue;
use AC\ThirdParty\MediaLibraryAssistant\WpListTableFactory;
use AC\Type\Uri;
use AC\Type\Url;
use MLACore;

class MediaLibrary extends AC\ListScreenPost implements AC\ListScreen\ListTable, AC\ListScreen\ManageValue
{

    public function __construct()
    {
        parent::__construct('mla-media-assistant', 'media_page_' . MLACore::ADMIN_PAGE_SLUG);

        $this->meta_type = MetaType::POST;
        $this->post_type = 'attachment';
        $this->group = 'media';
        $this->label = __('Media Library Assistant', 'codepress-admin-columns');
        $this->singular_label = __('Assistant', 'codepress-admin-columns');
    }

    public function get_table_url(): Uri
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
        $this->register_column_types_from_list([
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

}