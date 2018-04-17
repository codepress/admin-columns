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

	public static function string_to_classname( $string ) {
		return implode( array_map( 'ucfirst', explode( '_', str_replace( '-', '_', $string ) ) ) );
	}

	/**
	 * Register a prefix that should autoload
	 *
	 * @param $prefix string Unique prefix to this set of classes
	 * @param $dir    string Path to directory where classes are stored
	 */
	public function register_prefix( $prefix, $dir ) {
		$this->prefixes[ rtrim( $prefix, '_' ) ] = trailingslashit( $dir );

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
		$file = $class;

		// remove optional prefix
		if ( $class !== $prefix ) {
			$file = substr( $class, strlen( $prefix ) );
		}

		// concatenate path and add php extension
		$file = $dir . $file . '.php';

		// swap _ and \ to /
		$file = str_replace( array( '_', '\\' ), '/', $file );

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

	/**
	 * Get list of all auto-loadable class names from a directory
	 *
	 * @param string $dir
	 * @param bool   $use_namespaces Keep underscore until namespaces are mandatory
	 *
	 * @return array
	 */
	public function get_class_names_from_dir( $dir, $use_namespaces = false ) {
		if ( ! is_dir( $dir ) ) {
			return array();
		}

		$separator = $use_namespaces ? '\\' : '_';
		$class_base = false;

		foreach ( $this->prefixes as $prefix => $prefix_dir ) {
			if ( 0 !== strpos( $dir, $prefix_dir ) ) {
				continue;
			}

			$class_prefix = str_replace( $prefix_dir, '', $dir );
			$class_base = str_replace( '/', $separator, $prefix . '/' . $class_prefix . '/' );

			break;
		}

		if ( ! $class_base ) {
			return array();
		}

		$classes = array();
		$iterator = new \FilesystemIterator( $dir, \FilesystemIterator::SKIP_DOTS );

		foreach ( $iterator as $leaf ) {
			/* @var \DirectoryIterator $leaf */
			if ( $leaf->isDir() || 'php' !== $leaf->getExtension() ) {
				continue;
			}

			$r = new \ReflectionClass( $class_base . $leaf->getBasename( '.php' ) );

			if ( $r->isInstantiable() ) {
				$classes[] = $r->getName();
			}
		}

		return $classes;
	}

}