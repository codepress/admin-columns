<?php

namespace AC\Screen;

use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\ScreenController;
use AC\Table\LayoutPreference;
use AC\Table\PrimaryColumnFactory;
use AC\Type\ListScreenId;

class QuickEdit implements Registerable
{

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var LayoutPreference
     */
    private $preference;

	private $primary_column_factory;

	public function __construct(
		Storage $storage,
		LayoutPreference $preference,
		PrimaryColumnFactory $primary_column_factory
	) {
		$this->storage = $storage;
		$this->preference = $preference;
		$this->primary_column_factory = $primary_column_factory;
	}

    public function register()
    {
        add_action('admin_init', [$this, 'init_columns_on_quick_edit']);
    }

    /**
     * Get list screen when doing Quick Edit, a native WordPress ajax call
     */
    public function init_columns_on_quick_edit()
    {
        if ( ! wp_doing_ajax()) {
            return;
        }

        switch (filter_input(INPUT_POST, 'action')) {
            // Quick edit post
            case 'inline-save' :
                $type = filter_input(INPUT_POST, 'post_type');
                break;

            // Adding term & Quick edit term
            case 'add-tag' :
            case 'inline-save-tax' :
                $type = 'wp-taxonomy_' . filter_input(INPUT_POST, 'taxonomy');
                break;

            // Quick edit comment & Inline reply on comment
            case 'edit-comment' :
            case 'replyto-comment' :
                $type = 'wp-comments';
                break;

            default:
                return;
        }

        $id = $this->preference->get($type);

        if ( ! ListScreenId::is_valid_id($id)) {
            return;
        }

        $list_screen = $this->storage->find(new ListScreenId($id));

        if ( ! $list_screen || ! $list_screen->is_user_allowed(wp_get_current_user())) {
            return;
        }

		if ( ! $list_screen ) {
			return;
		}

		add_filter( 'list_table_primary_column', [ $this->primary_column_factory->create( $list_screen ), 'set_primary_column' ], 20 );

		$screen_controller = new ScreenController( $list_screen );
		$screen_controller->register();
	}

}