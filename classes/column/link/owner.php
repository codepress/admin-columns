<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Link_Owner
 *
 * @since 2.0
 */
class CPAC_Column_Link_Owner extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-owner';
		$this->properties['label'] = __( 'Owner', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		$value = $bookmark->link_owner;

		// add user link
		$userdata = get_userdata( $bookmark->link_owner );
		if ( ! empty( $userdata->data ) ) {
			$value = $userdata->data->user_nicename;
		}

		return $value;
	}
}