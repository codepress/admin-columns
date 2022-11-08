<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Integration;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Registerable;
use AC\Screen;
use Exception;

final class AddonAvailable
	implements Registerable {

	/**
	 * @var Integration
	 */
	private $integration;

	/**
	 * @var bool
	 */
	private $is_acp_active;

	public function __construct( Integration $integration, bool $is_acp_active ) {
		$this->integration = $integration;
		$this->is_acp_active = $is_acp_active;
	}

	/**
	 * @throws Exception
	 */
	public function register() {
		add_action( 'ac/screen', [ $this, 'display' ] );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	private function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_dismiss_notice_addon_' . $this->integration->get_slug() )
			->set_callback( [ $this, 'ajax_dismiss_notice' ] );

		return $handler;
	}

	/**
	 * @return Preferences\User
	 */
	private function get_preferences() {
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
		if (
			$this->is_acp_active
			|| ! current_user_can( Capabilities::MANAGE )
			|| ! $this->integration->show_notice( $screen )
			|| ! $this->integration->is_plugin_active()
			|| $this->get_preferences()->get( 'dismiss-notice' )
		) {
			return;
		}

		$support_text = sprintf(
			__( 'Did you know Admin Columns Pro has full support for %s?', 'codepress-admin-columns' ),
			sprintf( '<strong>%s</strong>', $this->integration->get_title() )
		);

		$link = sprintf( '<a href="%s">%s</a>', 'http://www.google.com', __( 'Get Admin Columns Pro', 'codepress-admin-columns' ) );
		$message = sprintf( '%s %s', $support_text, $link );

		$notice = new Dismissible( $message, $this->get_ajax_handler() );
		$notice->register();
	}

}