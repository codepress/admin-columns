<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Link_Description
 *
 * @since 2.0
 */
class CPAC_Column_Link_Description extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-description';
		$this->properties['label'] = __( 'Description', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $bookmark->link_description;
	}
}