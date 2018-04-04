<?php

class AC_Notices {

	/**
	 * @var AC_Notice[]
	 */
	protected static $notices = array();

	/**
	 * @param AC_Notice $notice
	 */
	public static function add( AC_Notice $notice ) {
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