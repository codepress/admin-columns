<?php

class AC_Settings_Column_Separator extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $separator;

	protected function define_options() {
		return array( 'separator' => 'comma' );
	}

	public function create_view() {
		$element = $this
			->create_element( 'select' )
			->set_options( array(
				''        => __( 'Single Space', 'codepress-admin-columns' ),
				'comma'   => __( 'Comma Separated', 'codepress-admin-columns' ),
				'newline' => __( 'New line', 'codepress-admin-columns' ),
			) );

		$view = new AC_View( array(
			'label'   => __( 'Separator', 'codepress-admin-columns' ),
			'tooltip' => __( 'Select a repeater sub field.', 'codepress-admin-columns' ),
			'setting' => $element,
		) );

		return $view;
	}

	public function get_separator() {
		return $this->separator;
	}

	public function set_separator( $separator ) {
		$this->separator = $separator;

		return $this;
	}

	public function get_formatted_separator() {

		switch ( $this->separator ) {
			case 'comma' :
				$separator = ', ';
				break;
			case 'newline' :
				$separator = '<br/>';
				break;
			case 'none' :
				$separator = '';
				break;
			default :
				$separator = '&nbsp;';
		}

		return $separator;
	}

}