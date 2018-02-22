<?php

class AC_Notices {

	/**
	 * @var AC_Notice[]
	 */
	protected static $notices = array();

	/**
	 * Register hooks to render the notices
	 */
	protected static function register() {
		$render_callback = array( __CLASS__, 'render' );

		add_action( 'admin_notices', $render_callback );
		add_action( 'network_admin_notices', $render_callback );
	}

	/**
	 * @param AC_Notice $notice
	 */
	public static function add( AC_Notice $notice ) {
		if ( empty( self::$notices ) ) {
			self::register();
		}

		self::$notices[] = $notice;

		add_action( 'admin_enqueue_scripts', array( $notice, 'scripts' ) );
	}

	public static function display() {
		$notices = array();

		foreach ( self::$notices as $notice ) {
			$output = $notice->render();

			if ( ! empty( $output ) ) {
				$notices[] = $output;
			}
		}

		echo implode( array_unique( $notices ) );
	}

}