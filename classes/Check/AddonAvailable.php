<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Integration;
use AC\Message\Notice;
use AC\PluginInformation;
use AC\Preferences;
use AC\Registrable;
use AC\Screen;

class AddonAvailable
	implements Registrable {

	/** @var Integration */
	private $integration;

	/**
	 * @param Integration $integration
	 */
	public function __construct( Integration $integration ) {
		$this->integration = $integration;
	}

	/**
	 * @throws \Exception
	 */
	public function register() {
		add_action( 'ac/screen', array( $this, 'display' ) );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_dismiss_notice_addon_' . $this->integration->get_slug() )
			->set_callback( array( $this, 'ajax_dismiss_notice' ) );

		return $handler;
	}

	/**
	 * @return Preferences\User
	 */
	protected function get_preferences() {
		return new Preferences\User( 'check-addon-available-' . $this->integration->get_slug() );
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

		if ( ! $this->integration->show_notice( $screen ) ) {
			return;
		}

		if ( ! $this->integration->is_plugin_active() ) {
			return;
		}

		if ( $this->get_preferences()->get( 'dismiss-notice' ) ) {
			return;
		}

		$integration_info = new PluginInformation( $this->integration->get_basename() );

		if ( $integration_info->is_active() ) {
			return;
		}

		$message = sprintf(
			__( 'Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!', 'codepress-admin-columns' ),
			sprintf( '<strong>%s</strong>', $this->integration->get_title() ),
			ac_helper()->html->link(
				AC()->admin()->get_link( 'addons' ),
				__( 'the addons page', 'codepress-admin-columns' )
			)
		);

		$notice = new Notice\Dismissible( $this->get_ajax_handler() );
		$notice->set_message( $message )
			->register();
	}

}