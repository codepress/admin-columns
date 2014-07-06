<?php
/**
 * CPAC_Column_User_Registered
 *
 * @since 2.0
 */
class CPAC_Column_User_Registered extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-user_registered';
		$this->properties['label']	 = __( 'Registered', 'cpac' );

		// Options
		$this->options['date_format'] = '';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $user_id ) {

		$user_registered = $this->get_raw_value( $user_id );

		// GMT offset is used
		return $this->get_date( get_date_from_gmt( $user_registered ), $this->options->date_format );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $user_id ) {

		$userdata = get_userdata( $user_id );

		return $userdata->user_registered;
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$this->display_field_date_format();
	}
}