<?php

namespace AC;

use FilesystemIterator;

class Autoloader {

	/**
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Register prefixes and their path
	 * @var string[]
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
	 *
	 * @return $this
	 */
	public function register_prefix( $prefix, $dir ) {
		$this->prefixes[ $prefix ] = trailingslashit( $dir );

		// make sure that more specific prefixes are checked first
		krsort( $this->prefixes );

		return $this;
	}

	/**
	 * @param $class
	 *
	 * @return false|string
	 */
	protected function get_prefix( $class ) {
		foreach ( array_keys( $this->prefixes ) as $prefix ) {
			if ( 0 === strpos( $class, $prefix ) ) {
				return $prefix;
			}
		}

		return false;
	}

	/**
	 * @param $prefix
	 *
	 * @return false|string
	 */
	protected function get_path( $prefix ) {
		if ( ! isset( $this->prefixes[ $prefix ] ) ) {
			return false;
		}

		return $this->prefixes[ $prefix ];
	}

	/**
	 * Get the path from a given namespace that has a registered prefix
	 *
	 * @param string $namespace
	 *
	 * @return false|string
	 */
	protected function get_path_from_namespace( $namespace ) {
		$prefix = $this->get_prefix( $namespace );

		if ( ! $prefix ) {
			return false;
		}

		$path = $this->get_path( $prefix ) . substr( $namespace, strlen( $prefix ) );
		$path = str_replace( '\\', '/', $path );

		return $path;
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	public function autoload( $class ) {
		$path = $this->get_path_from_namespace( $class );
		$file = realpath( $path . '.php' );

		if ( ! $file ) {
			return false;
		}

		require_once $file;

		return true;
	}

	/**
	 * Get list of all auto-loadable class names from a directory
	 *
	 * @param $namespace
	 *
	 * @return array
	 */
	public function get_class_names_from_dir( $namespace ) {
		$path = $this->get_path_from_namespace( $namespace );
		$path = realpath( $path );

		if ( ! $path ) {
			return array();
		}

		$iterator = new FilesystemIterator( $path, FilesystemIterator::SKIP_DOTS );
		$classes = array();

		/* @var \DirectoryIterator $leaf */
		foreach ( $iterator as $leaf ) {
			// Exclude system files
			if ( 0 === strpos( $leaf->getBasename(), '.' ) ) {
				continue;
			}

			if ( 'php' === $leaf->getExtension() ) {
				$classes[] = $namespace . '\\' . pathinfo( $leaf->getBasename(), PATHINFO_FILENAME );
			}
		}

		return $classes;
	}
}