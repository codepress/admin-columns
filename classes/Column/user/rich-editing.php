<?php
defined( 'ABSPATH' ) or die();

class CPAC_Column_User_Rich_Editing extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-rich_editing';
		$this->properties['label'] = __( 'Visual Editor', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $user_id ) {
		return $this->get_icon_yes_or_no( $this->has_rich_editing( $user_id ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $user_id ) {
		return $this->has_rich_editing( $user_id );
	}

	private function has_rich_editing( $user_id ) {
		$userdata = get_userdata( $user_id );

		return $userdata->rich_editing;
	}
}