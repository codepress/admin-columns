<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_Description extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_description';
		$this->properties['label'] = __( 'Description', 'codepress-admin-columns' );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		return ac_helper()->string->trim_words( get_the_author_meta( 'user_description', $user_id ), $this->get_option( 'excerpt_length' ) );
	}

	function display_settings() {
		$this->field_settings->word_limit();
	}

}