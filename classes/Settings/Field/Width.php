<?php

class AC_Settings_Field_Width extends AC_Settings_FieldAbstract {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit = 'px';

	/**
	 * @return string
	 */
	protected function set_properties() {
		$this->properties = array( 'width', 'width_unit' );

		return $this;
	}

	public function render() {
		// todo: change layout to default input and change auto to Auto
		$width = new AC_Settings_Form_Element_Input( 'width' );
		$width->set_attribute( 'placeholder', __( 'auto', 'codepress-admin-columns' ) )
		      ->set_value( $this->get_width() );

		$width_unit = new AC_Settings_Form_Element_Radio( 'width_unit' );
		$width_unit->add_class( 'unit' )
		           ->set_options( array(
			           'px' => 'px',
			           '%'  => '%',
		           ) )
		           ->set_value( $this->get_width_unit() );

		$section = new AC_Settings_Section( $this->column );
		$section->add_element( $width )
		        ->add_element( $width_unit )
		        ->set_label( __( 'Width', 'codepress-admin-columns' ) )
		        ->set_view( 'field', new AC_Settings_View_Field_Width() );

		return $section->render();
	}

	/**
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * @param int $width
	 *
	 * @return $this
	 */
	public function set_width( $width ) {
		$this->width = $width;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_width_unit() {
		return $this->width_unit;
	}

	/**
	 * @param string $width_unit
	 *
	 * @return $this
	 */
	public function set_width_unit( $width_unit ) {
		$this->width_unit = $width_unit;

		return $this;
	}

}