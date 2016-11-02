<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Length extends AC_Column {

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