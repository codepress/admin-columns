<?php
/**
 * CPAC_Column_Link_Owner
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Owner extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-owner';
		$this->properties['label']	 	= __( 'Owner', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
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