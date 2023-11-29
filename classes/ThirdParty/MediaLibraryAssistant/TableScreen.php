<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\Column;
use AC\MetaType;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;
use AC\Type\Url;
use MLACore;

class TableScreen extends AC\TableScreen implements AC\ListScreen\ListTable, AC\TableScreen\MetaType
{

    public function __construct()
    {
        parent::__construct(
            new ListKey('mla-media-assistant'),
            'media_page_' . MLACore::ADMIN_PAGE_SLUG,
            [
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
            ]
        );
    }

    public function list_table(): AC\ListTable
    {
        return new ListTable((new WpListTableFactory())->create());
    }

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    public function get_labels(): Labels
    {
        return new Labels(
            __('Assistant', 'codepress-admin-columns'),
            __('Media Library Assistant', 'codepress-admin-columns')
        );
    }

    public function manage_value(AC\ListScreen $list_screen): AC\Table\ManageValue
    {
        return new ManageValue($list_screen);
    }

    public function get_query_type(): string
    {
        return MetaType::POST;
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

    public function get_attr_id(): string
    {
        // TODO test
        return '#the-list';
    }

    public function get_url(): Uri
    {
        return new Url\ListTable\Media(null, MLACore::ADMIN_PAGE_SLUG);
    }

}