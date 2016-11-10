<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View_BeforeAfter extends AC_Settings_ViewAbstract {

	public function __construct( AC_Column $column ) {
		parent::__construct( $column );

		$this->set_label( __( 'Display Options', 'codepress-admin-columns' ) );
	}

	public function render() {
		$this->add_view( new AC_Settings_View( $this->column ) )
		     ->add_element( new AC_Settings_Form_Element_Input( 'before' ) )
		     ->set_label( __( 'Before', 'codepress-admin-columns' ) )
		     ->set_description( __( 'This text will appear before the column value.', 'codepress-admin-columns' ) );

		$this->add_view( new AC_Settings_View( $this->column ) )
		     ->add_element( new AC_Settings_Form_Element_Input( 'after' ) )
		     ->set_label( __( 'After', 'codepress-admin-columns' ) )
		     ->set_description( __( 'This text will appear after the column value.', 'codepress-admin-columns' ) );

		return $this->render_wrapper( $this->render_views() );
	}

}