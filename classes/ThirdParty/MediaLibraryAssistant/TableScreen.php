<?php

declare(strict_types=1);

namespace AC\ThirdParty\MediaLibraryAssistant;

use AC;
use AC\MetaType;
use AC\Type\Labels;
use AC\Type\TableId;
use AC\Type\PostTypeSlug;
use AC\Type\Url;
use MLACore;

class TableScreen extends AC\TableScreen implements AC\TableScreen\ListTable, AC\TableScreen\MetaType, AC\PostType
{

    public function __construct()
    {
        parent::__construct(
            new TableId('mla-media-assistant'),
            'media_page_' . MLACore::ADMIN_PAGE_SLUG,
            new Labels(
                __('Assistant', 'codepress-admin-columns'),
                __('Media Library Assistant', 'codepress-admin-columns')
            ),
            new Url\ListTable\Media(MLACore::ADMIN_PAGE_SLUG)
        );
    }

    public function get_post_type(): PostTypeSlug
    {
        return new PostTypeSlug('attachment');
    }

    public function list_table(): AC\ListTable
    {
        return new ListTable(new WpListTableFactory());
    }

    public function get_meta_type(): MetaType
    {
        return new MetaType(MetaType::POST);
    }

}