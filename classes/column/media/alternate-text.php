<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Media_Alternate_Text
 *
 * @since 2.0
 */
class CPAC_Column_Media_Alternate_Text extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 = 'column-alternate_text';
		$this->properties['label']	 = __( 'Alt', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->strip_trim( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return get_post_meta( $id, '_wp_attachment_image_alt', true );
	}
}