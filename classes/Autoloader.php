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

	/**
	 * @param $class
	 */
	public function autoload( $class ) {
		foreach ( $this->prefixes as $prefix => $path ) {
			if ( 0 !== strpos( $class, $prefix ) ) {
				continue;
			}

			$file = $path . str_replace( array( $prefix, '_' ), array( '', '/' ), $class ) . '.php';

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
	private function get_path_by_prefix( $prefix ) {
		return isset( $this->prefixes[ $prefix ] ) ? $this->prefixes[ $prefix ] : false;
	}

	/**
	 * Get list of all class names from a directory
	 *
	 * @param string $dir
	 * @param string $prefix
	 *
	 * @return array Class names
	 */
	public function get_class_names_from_dir( $dir, $prefix ) {
		$path = trailingslashit( $dir );
		$classes_dir = $this->get_path_by_prefix( $prefix );

		// skip if directory is not auto loaded
		if ( false === strpos( $path, $classes_dir ) ) {
			return array();
		}

		$class_names = array();

		$prefix = $prefix . str_replace( array( $classes_dir, '/' ), array( '', '_' ), untrailingslashit( $path ) ) . '_';

		if ( is_dir( $dir ) ) {
			$iterator = new DirectoryIterator( $dir );

			foreach ( $iterator as $leaf ) {
				// skip non php files
				if ( $leaf->isDot() || $leaf->isDir() || 'php' !== pathinfo( $leaf->getFilename(), PATHINFO_EXTENSION ) ) {
					continue;
				}

				$class_name = $prefix . str_replace( '.php', '', $leaf->getFilename() );

				$r = new ReflectionClass( $class_name );

				if ( $r->isInstantiable() ) {
					$class_names[] = $class_name;
				}
			}
		}

		return $class_names;
	}

}