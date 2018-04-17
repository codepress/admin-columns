<?php

namespace AC;

class Autoloader {

	/**
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Register prefixes and their path
	 *
	 * @var array
	 */
	protected $prefixes;

	protected function __construct() {
		$this->prefixes = array();

		spl_autoload_register( array( $this, 'autoload' ) );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register a prefix that should autoload
	 *
	 * @param $prefix string Unique prefix to this set of classes
	 * @param $dir    string Path to directory where classes are stored
	 */
	public function register_prefix( $prefix, $dir ) {
		$this->prefixes[ $prefix ] = trailingslashit( $dir );

		// make sure that more specific prefixes are checked first
		krsort( $this->prefixes );
	}

	/**
	 * @param string $class
	 * @param string $prefix
	 * @param string $dir
	 *
	 * @return string
	 */
	protected function classname_to_file( $class, $prefix, $dir ) {
		// remove prefix
		$file = substr( $class, strlen( $prefix ) );

		// concatenate path and add php extension
		$file = $dir . $file . '.php';

		// swap \ to /
		$file = str_replace( '\\', '/', $file );

		return $file;
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function autoload( $class ) {
		foreach ( $this->prefixes as $prefix => $dir ) {
			if ( 0 !== strpos( $class, $prefix ) ) {
				continue;
			}

			$file = $this->classname_to_file( $class, $prefix, $dir );

			if ( ! is_readable( $file ) ) {
				continue;
			}

			require_once $file;

			return true;
		}

		return false;
	}

}