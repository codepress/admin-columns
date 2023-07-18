<?php

namespace AC\ThirdParty\MediaLibraryAssistant\ListScreen;

use AC;
use AC\ColumnRepository;
use AC\ThirdParty\MediaLibraryAssistant\ManageValue;
use AC\ThirdParty\MediaLibraryAssistant\WpListTableFactory;
use MLACore;
use MLAData;

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

    public function get_object($post_id)
    {
        // Author column depends on this global to be set.
        global $authordata;

        $authordata = get_userdata(get_post_field('post_author', $post_id));

        if ( ! class_exists('MLAData')) {
            require_once(MLA_PLUGIN_PATH . 'includes/class-mla-data.php');
            MLAData::initialize();
        }

        return (object)MLAData::mla_get_attachment_by_id($post_id);
    }

    public function get_list_table($args = [])
    {
        return (new WpListTableFactory())->create();
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