<?php
/**
 * CPAC_Column_Link_Owner
 *
 * @since 2.0
 */
class CPAC_Column_Link_Owner extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-owner';
		$this->properties['label']	 	= __( 'Owner', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
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