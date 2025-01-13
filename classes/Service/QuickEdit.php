<?php

namespace AC\Service;

use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Table\LayoutPreference;
use AC\Table\PrimaryColumnFactory;
use AC\TableScreenFactory;
use AC\Type\ListKey;

class QuickEdit implements Registerable
{

    private $storage;

    private $preference;

    private $primary_column_factory;

    private $table_screen_factory;

    public function __construct(
        Storage $storage,
        LayoutPreference $preference,
        PrimaryColumnFactory $primary_column_factory,
        TableScreenFactory $table_screen_factory
    ) {
        $this->storage = $storage;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function register(): void
    {
        add_action('admin_init', [$this, 'init_columns_on_quick_edit']);
    }

    /**
     * Get list screen when doing Quick Edit, a native WordPress ajax call
     */
    public function init_columns_on_quick_edit(): void
    {
        if ( ! wp_doing_ajax()) {
            return;
        }

        switch (filter_input(INPUT_POST, 'action')) {
            // Quick edit post
            case 'pll_update_post_rows': // TODO move to polylang and out of this class
            case 'inline-save' :
                $list_key = (string)filter_input(INPUT_POST, 'post_type');
                break;

            // Adding term & Quick edit term
            case 'pll_update_term_rows': // TODO move to polylang and out of this class
            case 'add-tag' :
            case 'inline-save-tax' :
                $list_key = 'wp-taxonomy_' . filter_input(INPUT_POST, 'taxonomy');
                break;

            // Quick edit comment & Inline reply on comment
            case 'edit-comment' :
            case 'replyto-comment' :
                $list_key = 'wp-comments';
                break;

            default:
                return;
        }

        $list_id = $this->preference->find_list_id(new ListKey($list_key));

        if ( ! $list_id) {
            return;
        }

        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen || ! $list_screen->is_user_allowed(wp_get_current_user())) {
            return;
        }

        $table_screen = $this->table_screen_factory->create($list_screen->get_key());

        add_filter(
            'list_table_primary_column',
            [$this->primary_column_factory->create($list_screen), 'set_primary_column'],
            20
        );

        (new ManageHeadings())->handle($list_screen, $table_screen);
        (new ManageValue())->handle($list_screen, $table_screen);
    }

}