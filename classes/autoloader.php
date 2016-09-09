<?php

class AC_Autoloader {

	/**
	 * @var AC_Autoloader;
	 */
	protected static $instance;

	/**
	 * Register prefixes and their path
	 *
	 * @var array
	 */
	protected $prefixes = array();

	private function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a prefix that should autoload
	 *
	 * @param $prefix string Unique prefix to this set of classes
	 * @param $path string Path to directory where classes are stored
	 */
	public function register_prefix( $prefix, $path ) {
		$prefix = rtrim( $prefix, '_' ) . '_';
		$path = trailingslashit( $path );

		$this->prefixes[ $prefix ] = $path;

		// make sure that more specific prefixes are checked first
		krsort( $this->prefixes );
	}

	public function autoload( $class ) {
		foreach ( $this->prefixes as $prefix => $path ) {
			if ( strpos( $class, $prefix ) !== 0 ) {
				continue;
			}

			$file = $path . str_replace( '_', '/', str_replace( $prefix, '', $class ) ) . '.php';

			if ( is_readable( $file ) ) {
				require_once $file;

				break;
			}
		}
	}

	/**
	 * @param string $prefix
	 *
	 * @return false|string
	 */
	public function get_path_by_prefix( $prefix ) {
		return isset( $this->prefixes[ $prefix ] ) ? $this->prefixes[ $prefix ] : false;
	}

}