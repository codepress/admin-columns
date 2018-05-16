<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message\Notice;
use AC\Preferences;
use AC\Registrable;
use AC\Screen;

class AddonAvailable
	implements Registrable {

	public function register() {
		add_action( 'ac/screen', array( $this, 'display' ) );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	private function get_ajax_handler() {
		$handler = new Ajax\Handler();

		$handler->set_action( 'ac_dismiss_notice_addon_available' )
		        ->set_callback( array( $this, 'ajax_dismiss_notice' ) );

		return $handler;
	}

	/**
	 * @return Preferences\User
	 */
	protected function get_preferences() {
		return new Preferences\User( 'check-addon-available' );
	}

	/**
	 * Dismiss notice
	 */
	public function ajax_dismiss_notice() {
		$this->get_ajax_handler()->verify_request();
		$this->get_preferences()->set( 'dismiss-notice', true );
	}

	/**
	 * @param Screen $screen
	 */
	public function display( Screen $screen ) {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		if ( $this->get_preferences()->get( 'dismiss-notice' ) ) {
			return;
		}

		$titles = array();

		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( ! $addon->is_plugin_active() ) {
				continue;
			}

			if ( $addon->is_active() ) {
				continue;
			}

			if ( ! $screen->is_list_screen() && ! $screen->is_admin_screen() && ! $addon->is_notice_screen() ) {
				continue;
			}

			$titles[] = '<strong>' . $addon->get_title() . '</strong>';
		}

		if ( ! $titles ) {
			return;
		}

		$message = sprintf( __( "Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!", 'codepress-admin-columns' ), ac_helper()->string->enumeration_list( $titles, 'and' ), ac_helper()->html->link( AC()->admin()->get_link( 'addons' ), __( 'the addons page', 'codepress-admin-columns' ) ) );

		$notice = new Notice\Dismissible( $this->get_ajax_handler() );
		$notice->set_message( $message )
		       ->register();
	}

}