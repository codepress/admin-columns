<?php

class AC_Settings_Field_Type extends  {

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @return string
	 */
	protected function set_properties() {
		$this->properties = array( 'type' );

		return $this;
	}

	public function render() {


		$section = new AC_Settings_Section( $this->column );
		$section->add_element( $width )
		     ->add_element( $width_unit )
		     ->set_label( __( 'Width', 'codepress-admin-columns' ) )
		     ->set_view( 'field', new AC_Settings_View_Field_Width() );

		return $section->render();
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}



}