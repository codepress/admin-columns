<?php

class AC_NoticeManager {

	/**
	 * $GLOBALS['current_screen'] must be populated and list screens loaded
	 * before trying to display notices
	 */
	public function __construct() {
		add_action( 'current_screen', array( $this, 'review_notice' ), 11 );
		add_action( 'current_screen', array( $this, 'integration_notice' ), 11 );
	}

	/**
	 * Ask for a review after 30 days
	 */
	public function review_notice() {
		if ( AC()->suppress_site_wide_notices() || ! AC()->user_can_manage_admin_columns() ) {
			return;
		}

		$notice = new AC_ReviewNotice();
		$notice->register();

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