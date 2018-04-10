<?php

// TODO: remove once integrated
class AC_NoticeManager {

	public function __construct() {
		add_action( 'init', array( $this, 'review_notice' ) );
		add_action( 'init', array( $this, 'integration_notice' ) );
	}

	/**
	 * Ask for a review after 30 days
	 */
	public function review_notice() {
		if ( AC()->suppress_site_wide_notices() || ! current_user_can( AC_Capabilities::MANAGE ) ) {
			return;
		}

		$notice = new AC_ReviewNotice();
		$notice->register();

		// TODO: move to notice in question
		if ( ! $notice->is_dismissed() && $notice->first_login_compare( 30 ) ) {
			$notice->display();
		}
	}

	/**
	 * Ask to install missing add-ons
	 */
	public function integration_notice() {
		if ( AC()->suppress_site_wide_notices() || ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$notice = new AC_IntegrationPromoNotice();
		$notice->register();

		if ( ! $notice->is_dismissed() ) {
			$notice->display();
		}
	}

}