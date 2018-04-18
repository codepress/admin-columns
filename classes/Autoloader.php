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

		print_r( $class );
		echo "\n";

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

	public static function string_to_classname( $string ) {
		return implode( array_map( 'ucfirst', explode( '_', str_replace( '-', '_', $string ) ) ) );
	}

	/**
	 * Check if a file contains a class with a namespace
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	protected function is_namespace( $file ) {
		$tokens = token_get_all( file_get_contents( $file ) );

		foreach ( $tokens as $token ) {
			if ( is_array( $token ) && token_name( $token[0] ) == 'T_NAMESPACE' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a directory matches with a prefix
	 *
	 * @param $dir
	 *
	 * @return string|false
	 */
	protected function get_prefix( $dir ) {
		foreach ( $this->prefixes as $prefix => $prefix_dir ) {
			if ( 0 === strpos( $dir, $prefix_dir ) ) {
				return $prefix;
			}
		}

		return false;
	}

	/**
	 * Check if a class can be instantiated
	 *
	 * @param string $class
	 *
	 * @return bool
	 */
	protected function is_instantiable( $class ) {
		$r = new \ReflectionClass( $class );

		return $r->isInstantiable();
	}

	/**
	 * Get list of all auto-loadable class names from a directory
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	public function get_class_names_from_dir( $dir ) {
		$list = array();

		if ( ! is_dir( $dir ) ) {
			return $list;
		}

		$prefix = $this->get_prefix( $dir );

		if ( ! $prefix ) {
			return $list;
		}

		$class_prefix = $prefix . '/' . str_replace( $this->prefixes[ $prefix ], '', $dir ) . '/';
		$iterator = new \FilesystemIterator( $dir, \FilesystemIterator::SKIP_DOTS );

		foreach ( $iterator as $leaf ) {
			/* @var \DirectoryIterator $leaf */
			if ( $leaf->isDir() || 'php' !== $leaf->getExtension() ) {
				continue;
			}

			$separator = $this->is_namespace( $leaf->getPathname() ) ? '\\' : '_';
			$class = str_replace( '/', $separator, $class_prefix ) . $leaf->getBasename( '.php' );

			if ( ! $this->is_instantiable( $class ) ) {
				continue;
			}

			$list[] = $class;
		}

		return $list;
	}
}