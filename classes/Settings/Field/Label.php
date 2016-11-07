<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Label extends AC_Settings_FieldAbstract {

	//private $hide_label;

	//private $placeholder;

	public function __construct() {
		$this->set_type( 'label' );
	}

	/*public function set_hide_label( $boolean ) {
		$this->hide_label = $boolean;
	}*/

/*	public function set_placeholder( $placeholder ) {
		$this->placeholder = $placeholder;
	}*/

	public function hide_label() {
		if ( $this->settings->column->is_original() && ac_helper()->string->contains_html_only( $this->settings->get_option( 'label' ) ) ) {
			return true;
		}

		return false;
	}

	public function get_args() {
		return array(
			'type'        => 'text',
			'name'        => 'label',
			//'placeholder' => $this->column->get_label(),
			'placeholder' => $this->settings->column->get_type(),
			'label'       => __( 'Label', 'codepress-admin-columns' ),
			'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			//'hidden'      => $this->column->is_hide_label(),
			'hidden'      => $this->hide_label(),
		);
	}

	public function get_value() {

		// TODO: sanitize?

		return $this->settings->get_option( 'label' );
	}

}