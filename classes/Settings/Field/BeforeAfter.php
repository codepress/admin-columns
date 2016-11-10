<?php

class AC_Settings_Field_BeforeAfter extends AC_Settings_FieldAbstract {

	public function __construct( array $settings ) {
		parent::__construct( $settings );

		$this->set_label( __( 'Display Options', 'codepress-admin-columns' ) );
	}

	public function render_field() {
		$this->add_field( new AC_Settings_Field_Input() )
		     ->add_element( new AC_Settings_Form_Element_Input( 'before' ) )
		     ->set_label( __( 'Before', 'codepress-admin-columns' ) )
		     ->set_description( __( 'This text will appear before the column value.', 'codepress-admin-columns' ) );

		$this->add_field( new AC_Settings_Field_Input() )
		     ->add_element( new AC_Settings_Form_Element_Input( 'after' ) )
		     ->set_label( __( 'After', 'codepress-admin-columns' ) )
		     ->set_description( __( 'This text will appear after the column value.', 'codepress-admin-columns' ) );

		return $this->render_fields();
	}

}