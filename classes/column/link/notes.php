<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Link_Notes
 *
 * @since 2.0
 */
class CPAC_Column_Link_Notes extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-notes';
		$this->properties['label'] = __( 'Notes', 'codepress-admin-columns' );

		$this->options['excerpt_length'] = 30;
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->get_shortened_string( $bookmark->link_notes, $this->get_option( 'excerpt_length' ) );
	}

	function display_settings() {
		$this->display_field_excerpt_length();
	}
}