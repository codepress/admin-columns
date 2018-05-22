<?php

namespace AC\Form\Element;

use AC\Form\Element;

class Input extends Element {

	protected function is_valid_type( $type ) {
		$valid_types = array(
			'hidden',
			'text',
			'number',
			'email',
			'radio',
			'checkbox',
		);

		return in_array( $type, $valid_types );
	}

	public function render() {
		$template = '<input %s>%s';

		$attributes = $this->get_attributes();
		$attributes['name'] = $this->get_name();
		$attributes['id'] = $this->get_id();
		$attributes['value'] = $this->get_value();
		$attributes['type'] = $this->get_type();

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_description() );
	}

	public function get_type() {
		$type = $this->get_attribute( 'type' );

		if ( ! $type ) {
			return 'text';
		}

		return strtolower( $type );
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		if ( $this->is_valid_type( $type ) ) {
			$this->set_attribute( 'type', $type );
		}

		return $this;
	}

}