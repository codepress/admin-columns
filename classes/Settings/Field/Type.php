<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Type extends AC_Settings_FieldAbstract {

	public function __construct() {
		$this->set_type( 'type' );
	}

	public function display() {
		$this->field( array(
			'type'            => 'select',
			'name'            => 'type',
			'label'           => __( 'Type', 'codepress-admin-columns' ),
			'description'     => __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->column->get_name() . '</em>',

			// TODO: move to this object
			'grouped_options' => AC()->settings()->get_tab( 'columns' )->get_grouped_columns(),
			'default_value'   => $this->column->get_type(),
		) );
	}

}