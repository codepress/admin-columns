<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_Form_ElementAbstract {

	/**
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Options for element like select
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * The elements value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Label
	 *
	 * @var string
	 */
	protected $label;

	public function __construct( $name = null, array $options = array() ) {
		if ( null != $name ) {
			$this->set_name( $name );
		}

		if ( ! empty( $options ) ) {
			$this->set_options( $options );
		}
	}

	public abstract function render();

	public function get_attribute( $key ) {
		if ( ! isset( $this->attributes[ $key ] ) ) {
			return false;
		}

		return $this->attributes[ $key ];
	}

	public function set_attribute( $key, $value ) {
		if ( 'value' === $key ) {
			$this->set_value( $value );

			return $this;
		}

		$this->attributes[ $key ] = $value;

		return $this;
	}

	public function get_attributes() {
		return $this->attributes;
	}

	public function set_attributes( array $attributes ) {
		foreach ( $attributes as $key => $value ) {
			$this->set_attribute( $key, $value );
		}

		return $this;
	}

	/**
	 * Get attributes as string
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	protected function get_attributes_as_string( array $attributes ) {
		$output = array();

		foreach ( $attributes as $key => $value ) {
			$output[] = $this->get_attribute_as_string( $key, $value );
		}

		return implode( ' ', $output );
	}

	/**
	 * Render an attribute
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return string
	 */
	protected function get_attribute_as_string( $key, $value = null ) {
		if ( null === $value ) {
			$value = $this->get_attribute( $key );
		}

		return ac_helper()->html->get_attribute_as_string( $key, $value );
	}

	public function get_name() {
		return $this->get_attribute( 'name' );
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		return $this->set_attribute( $name );
	}

	public function get_id() {
		return $this->get_attribute( 'id' );
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		return $this->set_attribute( $id );
	}

	public function get_value() {
		return $this->value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set_value( $value ) {
		$this->value = $value;

		return $this;
	}

	public function set_class( $class ) {
		$this->set_attribute( 'class', $class );

		return $this;
	}

	public function add_class( $class ) {
		$parts = explode( ' ', (string) $this->get_attribute( 'class' ) );
		$parts[] = $class;

		$this->set_class( implode( ' ', $class ) );

		return $this;
	}

	public function get_label() {
		return $this->label;
	}

	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

}