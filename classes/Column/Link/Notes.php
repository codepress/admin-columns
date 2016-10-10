<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Notes extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-notes';
		$this->properties['label'] = __( 'Notes', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return ac_helper()->string->trim_words( $bookmark->link_notes, $this->get_option( 'excerpt_length' ) );
	}

	function display_settings() {
		$this->field_settings->word_limit();
	}

}