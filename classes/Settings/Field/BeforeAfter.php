<?php

class AC_Settings_Field_BeforeAfter extends AC_Settings_FieldAbstract {

	// todo: maybe attach storage here
	public function __construct( ) {

	}

	protected function set_elements() {
		$before = new AC_Settings_Form_Element_Input( 'before' );
		$after = new AC_Settings_Form_Element_Input( 'after' );
		$label = new AC_Settings_View_Label( __( 'Display Options', 'codepress-admin-columns' ), $before );

		$this->add_element( $before )
		     ->add_element( $after )
		     ->set_label( $label );
	}

	public function render() {

	}

}