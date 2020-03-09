<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Promo;
use AC\Registrable;
use AC\Screen;

final class Promotion
	implements Registrable {

	/** @var Promo */
	private $promo;

	public function __construct( Promo $promo ) {
		$this->promo = $promo;
	}

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
			->set_action( 'ac_dismiss_notice_promo_' . $this->get_individual_slug() )
			->set_callback( [ $this, 'ajax_dismiss_notice' ] );

		return $handler;
	}

	private function get_individual_slug() {
		return $this->promo->get_slug() . $this->promo->get_date_range()->get_start()->format( 'Ymd' );
	}

	/**
	 * @return Preferences\User
	 */
	private function get_preferences() {
		return new Preferences\User( 'check-promo-' . $this->get_individual_slug() );
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
		if ( ! $this->promo->is_active()
		     || ! current_user_can( Capabilities::MANAGE )
		     || ! $screen->is_list_screen()
		     || $this->get_preferences()->get( 'dismiss-notice' )
		) {
			return;
		}

		$message = sprintf( __( 'Get %s now', 'codepress-admin-columns' ), '<strong>Admin Columns Pro</strong>' );
		$message = sprintf( '%s! <a target="_blank" href="%s">%s</a>', $this->promo->get_title(), $this->promo->get_url(), $message );

		$notice = new Dismissible( $message, $this->get_ajax_handler() );
		$notice->register();
	}
}