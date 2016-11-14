<?php

class AC_Settings_Form_Element_Input extends AC_Settings_Form_ElementAbstract
	implements AC_Settings_ViewInterface {

	public function render() {
		$template = '<input %s>';

		$attributes = $this->get_attributes();
		$attributes['name'] = $this->render_name();
		$attributes['id'] = $this->render_id();
		$attributes['value'] = $this->get_value();
		$attributes['type'] = $this->get_type();

		return sprintf( $template, $this->get_attributes_as_string( $attributes ) );
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
		$this->set_attribute( 'type', $type );

		return $this;
	}

}