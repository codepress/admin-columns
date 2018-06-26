<?php

namespace AC\Form\Element;

use AC\Form\Element;

class Select extends Element {

	/**
	 * @var string
	 */
	protected $no_result = '';

	protected function render_options( array $options ) {
		$output = array();

		foreach ( $options as $key => $option ) {
			if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {
				$output[] = $this->render_optgroup( $option );

				continue;
			}

			$output[] = $this->render_option( $key, $option );
		}

		return implode( "\n", $output );
	}

	protected function render_option( $key, $label ) {
		$template = '<option %s>%s</option>';
		$attributes = $this->get_option_attributes( $key );

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), esc_html( $label ) );
	}

	protected function get_option_attributes( $key ) {
		$attributes = array();
		$attributes['value'] = $key;

		if ( selected( $this->get_value(), $key, false ) ) {
			$attributes['selected'] = 'selected';
		}

		return $attributes;
	}

	/**
	 * @param array $group
	 *
	 * @return string
	 */
	protected function render_optgroup( array $group ) {
		$template = '<optgroup %s>%s</optgroup>';
		$attributes = array();

		if ( isset( $group['title'] ) ) {
			$attributes['label'] = esc_attr( $group['title'] );
		}

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $group['options'] ) );
	}

	public function render() {
		if ( ! $this->get_options() ) {
			return $this->get_no_result();
		}

		$template = '
			<select %s>
				%s
			</select>
			%s';

		$attributes = $this->get_attributes();
		$attributes['name'] = $this->get_name();
		$attributes['id'] = $this->get_id();

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $this->get_options() ), $this->render_description() );
	}

	/**
	 * @return string
	 */
	public function get_no_result() {
		return $this->no_result;
	}

	/**
	 * @param string $no_result
	 *
	 * @return $this
	 */
	public function set_no_result( $no_result ) {
		$this->no_result = (string) $no_result;

		return $this;
	}

}