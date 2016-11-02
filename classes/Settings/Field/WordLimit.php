<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_WordLimit extends AC_Settings_FieldAbstract {

	public function __construct() {
		$this->set_type( 'word_limit' );
	}

	public function display() {
		$this->field( array(
			'type'          => 'number',
			'name'          => 'excerpt_length',
			'label'         => __( 'Word Limit', 'codepress-admin-columns' ),
			'description'   => __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>',
			'default_value' => $this->get_default_value(),
		) );
	}

}