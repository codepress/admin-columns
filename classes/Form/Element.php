<?php

namespace AC\Form;

abstract class Element {

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

	/**
	 * Extra description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Setup element with base name and id
	 *
	 * @param string $name
	 * @param array $options
	 */
	public function __construct( $name, array $options = array() ) {
		$this->set_name( $name );
		$this->set_id( $name );
		$this->set_options( $options );
	}

	/**
	 * @return string|false
	 */
	protected function render_description() {
		if ( ! $this->get_description() ) {
			return false;
		}

		$template = '<p class="help-msg">%s</p>';

		return sprintf( $template, $this->get_description() );
	}

	/**
	 * Render this element
	 *
	 * @return string
	 */
	abstract public function render();

	/**
	 * @param $key
	 *
	 * @return string|false
	 */
	public function get_attribute( $key ) {
		if ( ! isset( $this->attributes[ $key ] ) ) {
			return false;
		}

		return trim( $this->attributes[ $key ] );
	}

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return $this
	 */
	public function set_attribute( $key, $value ) {
		if ( 'value' === $key ) {
			$this->set_value( $value );

			return $this;
		}

		$this->attributes[ $key ] = $value;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * @param array $attributes
	 *
	 * @return $this
	 */
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
		return $this->set_attribute( 'name', $name );
	}

	/**
	 * @return false|string
	 */
	public function get_id() {
		return $this->get_attribute( 'id' );
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		return $this->set_attribute( 'id', $id );
	}

	/**
	 * @return mixed
	 */
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

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function set_class( $class ) {
		$this->set_attribute( 'class', $class );

		return $this;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function add_class( $class ) {
		$parts = explode( ' ', (string) $this->get_attribute( 'class' ) );
		$parts[] = $class;

		$this->set_class( implode( ' ', $parts ) );

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( array $options ) {
		$this->options = $options;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

}