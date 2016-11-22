<?php

final class AC_Settings_Form_Element_Select extends AC_Settings_Form_ElementAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	protected $no_result;

	protected function render_options( array $options ) {
		$template = '<option %s>%s</option>';
		$output = array();

		foreach ( $options as $key => $option ) {
			if ( isset( $option['options'] ) && is_array( $option['options'] ) ) {
				$output[] = $this->render_optgroup( $option );

				continue;
			}

			$attributes = array();
			$attributes['value'] = $key;

			if ( selected( $this->get_value(), $key, false ) ) {
				$attributes['selected'] = 'selected';
			}

			$output[] = sprintf( $template, $this->get_attributes_as_string( $attributes ), esc_html( $option ) );
		}

		return implode( "\n", $output );
	}

	protected function render_optgroup( array $group ) {
		$template = '<optgroup %s>%s</optgroup>';
		$attributes = array();

		if ( isset( $group['title'] ) ) {
			$attributes['label'] = esc_attr( $group['title'] );
		}

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $group['options'] ) );
	}

	protected function render_ajax_message() {
		return '<div class="msg"></div>';
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

		return sprintf( $template, $this->get_attributes_as_string( $attributes ), $this->render_options( $this->get_options() ), $this->render_ajax_message() );
	}

	/**
	 * @return string
	 */
	public function get_no_result() {
		if ( empty( $this->no_result ) ) {
			return false;
		}

		return $this->no_result;
	}

	/**
	 * @param string $no_result
	 *
	 * @return AC_Settings_Form_Element_Select
	 */
	public function set_no_result( $no_result ) {
		$this->no_result = $no_result;

		return $this;
	}

}