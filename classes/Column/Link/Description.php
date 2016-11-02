<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Description extends AC_Column {

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