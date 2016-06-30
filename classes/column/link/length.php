<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Link_Length
 *
 * @since 2.0
 */
class CPAC_Column_Link_Length extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-length';
		$this->properties['label'] = __( 'Length', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return strlen( $bookmark->link_name );
	}
}