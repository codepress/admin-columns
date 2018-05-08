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

	/**
	 * Register aliases for _ classes
	 *
	 * @var array
	 */
	protected $aliases;

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
	 * Register alias
	 *
	 * @param string $class
	 * @param string $alias
	 *
	 * @return $this
	 */
	public function register_alias( $class, $alias ) {
		$this->aliases[ $class ] = $alias;

		return $this;
	}

	/**
	 * @param string $class
	 * @param string $prefix
	 * @param string $dir
	 *
	 * @return bool
	 */
	protected function require_file( $class, $prefix, $dir ) {
		// TODO: check alias
		// TODO: check _ ?

		// remove prefix
		$file = substr( $class, strlen( $prefix ) );

		// concatenate path and add php extension
		$file = $dir . $file . '.php';

		// swap \ to /
		$file = str_replace( '\\', '/', $file );

		// TODO: check _ ?

		if ( ! is_readable( $file ) ) {
			return false;
		}

		require_once $file;

		return true;
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function autoload( $class ) {
		foreach ( $this->prefixes as $prefix => $dir ) {
			if ( 0 === strpos( $class, $prefix ) ) {
				return $this->require_file( $class, $prefix, $dir );
			}
		}

		return false;
	}

	public static function string_to_classname( $string ) {
		return implode( array_map( 'ucfirst', explode( '_', str_replace( '-', '_', $string ) ) ) );
	}

	/**
	 * Get list of all auto-loadable class names from a directory
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	public static function get_class_names_from_dir( $dir ) {
		$list = array();

		if ( ! is_dir( $dir ) ) {
			return $list;
		}

		$iterator = new \FilesystemIterator( $dir, \FilesystemIterator::SKIP_DOTS );

		foreach ( $iterator as $leaf ) {
			/* @var \DirectoryIterator $leaf */
			if ( $leaf->isFile() && 'php' === $leaf->getExtension() && $leaf->isReadable() ) {
				$list[] = $leaf->getBasename();
			}
		}

		return $list;
	}
}