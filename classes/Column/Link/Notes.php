<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Notes extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-notes' );
		$this->set_label( __( 'Notes', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return ac_helper()->string->trim_words( $bookmark->link_notes, $this->get_option( 'excerpt_length' ) );
	}

	public function display_settings() {
		$this->field_settings->word_limit();
	}

}