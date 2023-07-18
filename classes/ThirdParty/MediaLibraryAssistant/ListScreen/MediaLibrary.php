<?php

namespace AC\ThirdParty\MediaLibraryAssistant\ListScreen;

use AC;
use AC\ColumnRepository;
use AC\ThirdParty\MediaLibraryAssistant\ManageValue;
use AC\ThirdParty\MediaLibraryAssistant\WpListTableFactory;
use MLACore;

class MediaLibrary extends AC\ListScreen\Media
{

    public function __construct()
    {
        parent::__construct();

        $this->set_key('mla-media-assistant')
             ->set_label(__('Media Library Assistant', 'codepress-admin-columns'))
             ->set_singular_label(__('Assistant', 'codepress-admin-columns'))
             ->set_screen_id('media_page_' . MLACore::ADMIN_PAGE_SLUG)
             ->set_page(MLACore::ADMIN_PAGE_SLUG);
    }

    public function manage_value(): AC\Table\ManageValue
    {
        return new ManageValue(new ColumnRepository($this));
    }

    public function list_table(): AC\ListTable
    {
        return new AC\ThirdParty\MediaLibraryAssistant\ListTable((new WpListTableFactory())->create());
    }

    /**
     * Remove duplicate columns that are provided by MLA
     */
    public function register_column_types(): void
    {
        parent::register_column_types();

        $exclude = [
            'comments',
            'title',
            'column-actions',
            'column-alternate_text',
            'column-attached_to',
            'column-author_name',
            'column-caption',
            'column-description',
            'column-file_name',
            'column-full_path',
            'column-mediaid',
            'column-mime_type',
            'column-taxonomy',
        ];

        array_map([$this, 'deregister_column_type'], $exclude);
    }

}