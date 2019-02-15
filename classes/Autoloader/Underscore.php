<?php

namespace AC\Autoloader;

use Exception;

class Underscore {

	/**
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Register aliases that point to a namespace
	 * @var string[]
	 */
	protected $aliases;

	protected function __construct() {
		$this->aliases = array();

		spl_autoload_register( array( $this, 'autoload' ), true, true );
	}

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Add a class alias
	 *
	 * @param        $original
	 * @param string $alias
	 *
	 * @return Underscore
	 * @throws Exception
	 */
	public function add_alias( $original, $alias ) {
		if ( strpos( $original, '/' ) ) {
			throw new Exception( 'Namespaces use \ instead of /.' );
		}

		if ( ! $this->register_alias( $original, $alias ) ) {
			throw new Exception( sprintf( 'Failed to register alias for %s', $original ) );
		}

		$this->aliases[ $alias ] = $original;

		return $this;
	}

	/**
	 * Check if original exists and if so, create the class alias
	 *
	 * @param string $original
	 * @param string $alias
	 *
	 * @return bool
	 */
	protected function register_alias( $original, $alias ) {
		if ( ! class_exists( $original ) && ! interface_exists( $original ) ) {
			return false;
		}

		class_alias( $original, $alias );

		return true;
	}

	/**
	 * @param $alias
	 *
	 * @return bool
	 */
	public function alias_exists( $alias ) {
		return isset( $this->aliases[ $alias ] );
	}

	/**
	 * @param $alias
	 *
	 * @return false|string
	 */
	public function get_original( $alias ) {
		if ( ! $this->alias_exists( $alias ) ) {
			return false;
		}

		return $this->aliases[ $alias ];
	}

	/**
	 * Check if the prefix is within the domain of Admin Columns
	 *
	 * @param string $alias
	 *
	 * @return bool
	 */
	protected function is_valid_prefix( $alias ) {
		$prefixes = array( 'AC', 'ACP', 'ACA' );

		foreach ( $prefixes as $prefix ) {
			if ( 0 === strpos( $alias, $prefix . '_' ) ) {
				return true;
			}
		}

		return false;
	}

	public function autoload( $alias ) {
		if ( ! $this->is_valid_prefix( $alias ) ) {
			return false;
		}

		$original = $this->get_original( $alias );

		if ( ! $original ) {
			$original = str_replace( '_', '\\', $alias );

			if ( ! $this->register_alias( $original, $alias ) ) {
				return false;
			}
		}

		if ( WP_DEBUG ) {
			$error = sprintf( '%s is a <strong>deprecated class</strong> since version %s! Use %s instead.', $alias, 4.3, $original );

			trigger_error( $error );
		}

		return true;
	}

}