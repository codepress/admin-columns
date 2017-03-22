<?php

class AC_Settings_Column_Separator extends AC_Settings_Column
	implements AC_Settings_FormatCollectionInterface {

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
				''            => __( 'Default', 'codepress-admin-columns' ),
				'comma'       => __( 'Comma Separated', 'codepress-admin-columns' ),
				'newline'     => __( 'New line', 'codepress-admin-columns' ),
				'none'        => __( 'None', 'codepress-admin-columns' ),
				'white_space' => __( 'Whitespace', 'codepress-admin-columns' ),
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

	/**
	 * @param AC_Collection $collection
	 * @param mixed         $original_value
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_Collection $collection, $original_value ) {
		switch ( $this->separator ) {

			case 'comma' :
				$separator = ', ';

				break;
			case 'newline' :
				$separator = "<br/>";

				break;
			case 'none' :
				$separator = '';

				break;
			case 'white_space' :
				$separator = '&nbsp;';

				break;
			default :
				$separator = $this->column->get_separator();
		}

		$value_formatter = new AC_ValueFormatter( $original_value );
		$value_formatter->value = $collection->implode( $separator );

		return $value_formatter;
	}

}