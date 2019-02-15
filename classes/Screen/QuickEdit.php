<?php

namespace AC\Screen;

use AC\ListScreen;
use AC\ListScreenFactory;
use AC\Registrable;

class QuickEdit implements Registrable {

	/**
	 * @var ListScreen
	 */
	private $list_screen;

	/**
	 * Register hooks
	 */
	public function register() {
		add_action( 'admin_init', array( $this, 'set_list_screen' ) );
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
	public function set_list_screen() {
		if ( ! $this->is_doing_ajax() ) {
			return;
		}

		$type = false;

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
		}

		if ( ! $type ) {
			return;
		}

		$this->list_screen = ListScreenFactory::create( $type );

		do_action( 'ac/screen/quick_edit', $this );
	}

	/**
	 * @return ListScreen
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

}