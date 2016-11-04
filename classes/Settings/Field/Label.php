<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Label extends AC_Settings_FieldAbstract {

	public function __construct() {
		$this->set_type( 'label' );
	}

	public function get_args() {
		return array(
			'type'        => 'text',
			'name'        => 'label',
			'placeholder' => $this->column->get_label(),
			'label'       => __( 'Label', 'codepress-admin-columns' ),
			'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'hidden'      => $this->column->is_hide_label(),
		);
	}

	/*public function display() {
		$this->field( array(
			'type'        => 'text',
			'name'        => 'label',
			'placeholder' => $this->column->get_label(),
			'label'       => __( 'Label', 'codepress-admin-columns' ),
			'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'hidden'      => $this->column->is_hide_label(),
		) );
	}*/

}