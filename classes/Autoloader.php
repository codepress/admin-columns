<?php

namespace AC;

use DirectoryIterator;
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

	/**
	 * @var string[]
	 */
	protected $class_map = [];

	protected function __construct() {
		$this->prefixes = [];

		spl_autoload_register( [ $this, 'autoload' ] );
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
	 * @param array $class_map
	 *
	 * @return $this
	 */
	public function register_class_map( array $class_map ) {
		$this->class_map = array_merge( $this->class_map, $class_map );

		// keep the classes organized for faster lookup
		ksort( $this->class_map );

		return $this;
	}

	/**
	 * @param $namespace
	 *
	 * @return false|string
	 */
	protected function get_prefix( $namespace ) {
		foreach ( array_keys( $this->prefixes ) as $prefix ) {
			if ( 0 === strpos( $namespace, $prefix ) ) {
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
		$namespace = rtrim( $namespace, '\\' );
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
		$file = array_key_exists( $class, $this->class_map )
			? $this->class_map[ $class ]
			: realpath( $this->get_path_from_namespace( $class ) . '.php' );

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
		$classes = [];
		$namespace = rtrim( $namespace, '\\' ) . '\\';

		foreach ( $this->class_map as $class => $path ) {
			// Check if it the same, but only 1 level deep
			if ( strpos( $class, $namespace ) !== 0 || false !== strpos( '\\', str_replace( $namespace, '', $class ) ) ) {
				continue;
			}

			$classes[] = $class;
		}

		if ( empty( $classes ) ) {
			$classes = $this->get_class_names_from_filesystem( $namespace );
		}

		return $classes;
	}

	/**
	 * @param string $namespace
	 *
	 * @return array
	 */
	protected function get_class_names_from_filesystem( $namespace ) {
		$classes = [];
		$namespace_path = realpath( $this->get_path_from_namespace( $namespace ) );

		if ( $namespace_path ) {
			$iterator = new FilesystemIterator( $namespace_path, FilesystemIterator::SKIP_DOTS );

			/* @var DirectoryIterator $leaf */
			foreach ( $iterator as $leaf ) {
				// Exclude system files
				if ( 0 === strpos( $leaf->getBasename(), '.' ) ) {
					continue;
				}

				if ( 'php' === $leaf->getExtension() ) {
					$classes[] = $namespace . pathinfo( $leaf->getBasename(), PATHINFO_FILENAME );
				}
			}
		}

		return $classes;
	}
}