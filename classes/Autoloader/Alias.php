<?php

namespace AC\Autoloader;

class Alias {

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

		// prepended autoload
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
	public function original_exists( $alias ) {
		return isset( $this->aliases[ $alias ] );
	}

	/**
	 * @param $alias
	 *
	 * @return false|string
	 */
	public function get_original( $alias ) {
		if ( ! $this->original_exists( $alias ) ) {
			return false;
		}

		return $this->aliases[ $alias ];
	}

	public function autoload( $alias ) {
		if ( ! $this->original_exists( $alias ) ) {
			return false;
		}

		class_alias( $this->get_original( $alias ), $alias );

		return true;
	}

}