<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Link_ID
 *
 * @since 2.0
 */
class CPAC_Column_Link_ID extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 	= 'column-link_id';
		$this->properties['label']	 	= __( 'ID', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		return $id;
	}
}