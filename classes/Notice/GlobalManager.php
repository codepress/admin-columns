<?php

class AC_Notice_GlobalManager {

	/**
	 * @var AC_Notice_Global[]
	 */
	protected static $notices = array();

	public static function register() {
		if ( self::suppress_site_wide_notices() ) {
			return;
		}

		add_action( 'admin_notices', 'AC_Notice_GlobalManager::display' );
		add_action( 'network_admin_notices', 'AC_Notice_GlobalManager::display' );
	}

	/**
	 * Prevent register function to automatically display notices
	 *
	 * @return bool
	 */
	public static function suppress_site_wide_notices() {
		return apply_filters( 'ac/suppress_site_wide_notices', false );
	}

	public static function add_notice( AC_Notice_Global $notice ) {
		self::$notices[] = $notice;
	}

	public static function display() {
		$notices = array();

		foreach ( self::$notices as $notice ) {
			$notices[] = $notice->render();
		}

		echo implode( '', array_filter( array_unique( $notices ) ) );
	}

}