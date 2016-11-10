<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_View_WordLimit extends AC_Settings_ViewAbstract {

	/**
	 * @var int
	 */
	protected $value;

	public function render() {
		$input = new AC_Settings_Form_Element_Input( 'excerpt_length' );
		$input->set_type( 'number' )
		      ->set_value( $this->get_value() )
		      ->set_attribute( 'min', 0 )
		      ->set_attribute( 'step', 1 );

		$this->set_label( __( 'Word Limit', 'codepress-admin-columns' ) )
		     ->set_description( __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>' )
		     ->set_element( $input );

		$this->render_layout();
	}

	public function set_value( $value ) {
		$this->value = absint( $value );
	}

	/**
	 * @return int
	 */
	public function get_value() {
		return $this->value;
	}

}