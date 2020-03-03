<?php

namespace AC\Screen;

use AC\ListScreenRepository\Storage;
use AC\Preferences\Site;
use AC\Registrable;
use AC\ScreenController;
use AC\Type\ListScreenId;

class QuickEdit implements Registrable {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var Site
	 */
	private $preferences;

	public function __construct( Storage $storage, Site $preferences ) {
		$this->storage = $storage;
		$this->preferences = $preferences;
	}

	public function register() {
		add_action( 'admin_init', array( $this, 'init_columns_on_quick_edit' ) );
	}

	/**
	 * @return bool
	 */
	private function is_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Get list screen when doing Quick Edit, a native WordPress ajax call
	 */
	public function init_columns_on_quick_edit() {
		if ( ! $this->is_doing_ajax() ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'action' ) ) {

			// Quick edit post
			case 'inline-save' :
				$type = filter_input( INPUT_POST, 'post_type' );
				break;

			// Adding term & Quick edit term
			case 'add-tag' :
			case 'inline-save-tax' :
				$type = 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' );
				break;

			// Quick edit comment & Inline reply on comment
			case 'edit-comment' :
			case 'replyto-comment' :
				$type = 'wp-comments';
				break;

			default:
				return;
		}

		$id = $this->preferences->get( $type );

		if ( ! $id ) {
			return;
		}

		$list_screen = $this->storage->find( new ListScreenId( $id ) );

		if ( ! $list_screen ) {
			return;
		}

		$screen_controller = new ScreenController( $list_screen );
		$screen_controller->register();
	}

}