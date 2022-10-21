<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Integration;
use AC\Message\Notice\Dismissible;
use AC\PluginInformation;
use AC\Preferences;
use AC\Registrable;
use AC\Screen;
use AC\Type\Url\Editor;
use Exception;

final class AddonAvailable
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
		// TODO test
		if ( ! current_user_can( Capabilities::MANAGE )
		     || ! $this->integration->show_notice( $screen )
		     || ! $this->integration->is_plugin_active()
		     || $this->get_preferences()->get( 'dismiss-notice' )
		     || ac_is_pro_active()
		) {
			return;
		}

		$integration_info = new PluginInformation( $this->integration->get_basename() );

		if ( $integration_info->is_active() ) {
			return;
		}

		$addon_url = new Editor( 'addons' );

		$message = sprintf(
			__( 'Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!', 'codepress-admin-columns' ),
			sprintf( '<strong>%s</strong>', $this->integration->get_title() ),
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( $addon_url->get_url() ),
				__( 'the addons page', 'codepress-admin-columns' )
			)
		);

		$notice = new Dismissible( $message, $this->get_ajax_handler() );
		$notice->register();
	}

}