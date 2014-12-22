<?php
/**
 * CPAC_Column_User_Rich_Editing
 *
 * @since 2.0
 */
class CPAC_Column_User_Rich_Editing extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-rich_editing';
		$this->properties['label']	 = __( 'Visual Editor', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $user_id ) {

		$value = $this->get_asset_image( 'no.png' );
		if ( 'true' === $this->get_raw_value( $user_id ) ) {
			$value = $this->get_asset_image( 'checkmark.png' );
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $user_id ) {

		$userdata = get_userdata( $user_id );

		return $userdata->rich_editing;
	}
}