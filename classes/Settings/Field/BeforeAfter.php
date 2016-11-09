<?php

class AC_Settings_Field_BeforeAfter extends AC_Settings_FieldAbstract {

	public function render_field() {
		$before = new AC_Settings_Field_Input();
		$before->add_element( new AC_Settings_Form_Element_Input( 'before' ) )
		       ->set_label( __( 'Before', 'codepress-admin-columns' ) )
		       ->set_description( __( 'This text will appear before the column value.', 'codepress-admin-columns' ) );

		$after = new AC_Settings_Field_Input();
		$after->add_element( new AC_Settings_Form_Element_Input( 'after' ) )
		      ->set_label( __( 'After', 'codepress-admin-columns' ) )
		      ->set_description( __( 'This text will appear after the column value.', 'codepress-admin-columns' ) );

		$this->set_label( __( 'Display Options', 'codepress-admin-columns' ) );
	}

}