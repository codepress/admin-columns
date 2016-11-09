<?php

class AC_Settings_Field_BeforeAfter extends AC_Settings_FieldAbstract {

	protected function set_fields() {
		$before = new AC_Settings_Form_Element_Input( 'before' );
		$before->set_label( 'Before' );

		$after = new AC_Settings_Form_Element_Input( 'after' );
		$label = new AC_Settings_View_Label( __( 'Display Options', 'codepress-admin-columns' ), $before );

		// todo: add view to element or the other way around? with label etc. I think a field is the logical choice

		$this->add_element( $before )
		     ->add_element( $after )
		     ->set_label( $label );
	}

	public function render() {
		$before =

		parent::render();

	}

}