<?php

namespace AC\Autoloader;

class Underscore {

	/**
	 * @var self;
	 */
	protected static $instance;

	/**
	 * Register aliases that point to a namespace
	 *
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
	 * @param string $class
	 * @param string $alias
	 *
	 * return $this
	 */
	public function add_alias( $original, $alias = null ) {
		if ( strpos( $original, '/' ) ) {
			throw new \Exception( 'Namespaces use \ instead of /.' );
		}

		if ( null === $alias ) {
			$alias = str_replace( '\\', '_', $original );
		}

		if ( ! strpos( $alias, '_' ) ) {
			throw new \Exception( 'Only use for deprecated _ class notation like AC_Column.' );
		}

		$this->aliases[ $alias ] = $original;

		return $this;
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

	public function autoload( $alias ) {
		if ( ! strpos( $alias, '_' ) ) {
			return false;
		}

		if ( $this->alias_exists( $alias ) ) {
			$this->class_alias( $this->get_original( $alias ), $alias );

			return true;
		}

		$prefixes = array( 'AC_', 'ACP_', 'ACA_' );

		foreach ( $prefixes as $prefix ) {
			if ( 0 === strpos( $alias, $prefix ) ) {
				$this->class_alias( str_replace( '_', '\\', $alias ), $alias );

				return true;
			}

		}

		return false;
	}

	protected function class_alias( $original, $alias ) {
		class_alias( $original, $alias );

		if ( WP_DEBUG ) {
			$error = sprintf( '%s is a <strong>deprecated class</strong> since version %s! Use %s instead.', $alias, 4.3, $original );

			trigger_error( $error );
		}
	}

}