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

	/**
	 * @param AC_Collection $collection
	 * @param int           $id
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_Collection $collection, $id ) {
		switch ( $this->separator ) {
			case 'comma' :
				$separator = ', ';
				break;
			case 'newline' :
				$separator = '<br/>';
				break;
			// TODO: none is not listed in the create_view
			case 'none' :
				$separator = '';
				break;
			default :
				// TODO: check if this makes sense over using &nbsp;
				$separator = $this->column->get_separator();
		}

		$value_formatter = new AC_ValueFormatter( $id );
		$value_formatter->value = $collection->implode( $separator );

		return $value_formatter;
	}

}