<?php

namespace AC\Table;

class Button {

	/** @var string $slug */
	private $slug;

	/** @var string $label */
	private $label;

	/** @var string $text */
	private $text;

	/** @var string $dashicon */
	private $dashicon;

	/** @var array */
	protected $attributes = [];

	public function __construct( $slug ) {
		$this->set_slug( $slug );
		$this->add_class( 'ac-table-button -' . $slug );
	}

	/**
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function add_class( $class ) {
		$this->set_attribute( 'class', $this->get_attribute( 'class' ) . ' ' . esc_attr( $class ) );

		return $this;
	}

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
		$this->attributes[ $key ] = $value;

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
		$output = [];

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

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 *
	 * @return $this
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;

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
	 * @return string
	 */
	public function get_text() {
		return $this->text;
	}

	/**
	 * @param string $text
	 *
	 * @return Button
	 */
	public function set_text( $text ) {
		$this->text = $text;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_dashicon() {
		if ( ! $this->dashicon ) {
			return '';
		}

		return ac_helper()->icon->dashicon( [
			'icon' => $this->dashicon,
		] );
	}

	/**
	 * @param $dashicon
	 *
	 * @return $this
	 */
	public function set_dashicon( $dashicon ) {
		$this->dashicon = $dashicon;

		return $this;
	}

	/**
	 * @param $url
	 *
	 * @return $this
	 */
	public function set_url( $url ) {
		$this->set_attribute( 'href', esc_url( $url ) );

		return $this;
	}

	public function render() {
		$attributes = $this->get_attributes();
		$attributes['data-ac-tip'] = $this->get_label();
		$attributes['data-slug'] = $this->get_slug();

		$template = '<a %s>%s%s</a>';

		echo sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->get_dashicon(), $this->get_text() );
	}

}