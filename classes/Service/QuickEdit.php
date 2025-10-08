<?php

namespace AC\Service;

use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Setting\ContextFactory;
use AC\Table\PrimaryColumnFactory;
use AC\Table\TablePreference;
use AC\TableScreenFactory;
use AC\Type\TableId;

class QuickEdit implements Registerable
{

    private Storage $storage;

    private TablePreference $preference;

    private PrimaryColumnFactory $primary_column_factory;

    private TableScreenFactory $table_screen_factory;

    private ContextFactory $context_factory;

    public function __construct(
        Storage $storage,
        TablePreference $preference,
        PrimaryColumnFactory $primary_column_factory,
        TableScreenFactory $table_screen_factory,
        ContextFactory $context_factory
    ) {
        $this->storage = $storage;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->context_factory = $context_factory;
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
            case 'pll_update_post_rows':
            case 'inline-save' :
                $table_id = (string)filter_input(INPUT_POST, 'post_type');
                break;

            // Adding term & Quick edit term
            case 'pll_update_term_rows':
            case 'add-tag' :
            case 'inline-save-tax' :
                $table_id = 'wp-taxonomy_' . filter_input(INPUT_POST, 'taxonomy');
                break;

            // Quick edit comment & Inline reply on comment
            case 'edit-comment' :
            case 'replyto-comment' :
                $table_id = 'wp-comments';
                break;

            default:
                return;
        }

        $list_id = $this->preference->get_list_id(new TableId($table_id));

        if ( ! $list_id) {
            return;
        }

        $list_screen = $this->storage->find($list_id);

        if ( ! $list_screen || ! $list_screen->is_user_allowed(wp_get_current_user())) {
            return;
        }

        $table_screen = $this->table_screen_factory->create($list_screen->get_table_id());

        add_filter(
            'list_table_primary_column',
            [$this->primary_column_factory->create($list_screen), 'set_primary_column'],
            20
        );

        (new ManageHeadings($this->context_factory))->handle($list_screen, $table_screen);
        (new ManageValue())->handle($list_screen, $table_screen);
    }

}