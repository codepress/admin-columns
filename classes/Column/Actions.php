<?php

/**
 * Base class for columns containing action links for items.
 *
 * @since 2.2.6
 */
abstract class AC_Column_Actions extends AC_Column {

	/**
	 * Get a list of action links for an item (e.g. post) ID.
	 *
	 * @since 2.2.6
	 *
	 * @param int $id Item ID to get the list of actions for.
	 *
	 * @return array List of actions ([action name] => [action link]).
	 */
	abstract protected function get_object_type();

	/**
	 * @since 2.2.6
	 */
	public function __construct() {
		$this->set_type( 'column-actions' );
		$this->set_label( __( 'Actions', 'codepress-admin-columns' ) );
	}

	/**
	 * @since 2.2.6
	 */
	public function get_value( $id ) {
		if ( $this->get_option( 'use_icons' ) ) {
			return '<span class="cpac_use_icons"></span>';
		}

		return '';
	}

	/**
	 * @see AC_Column::get_value()
	 * @since 2.2.6
	 */
	public function get_raw_value( $id ) {
		$actions = ac_action_column_helper()->get( $this->get_object_type(), $id );

		/**
		 * Filter the action links for the actions column
		 *
		 * @since 2.2.9
		 *
		 * @param array $actions List of actions ([action name] => [action link]).
		 * @param AC_Column_Actions $this Column object.
		 * @param int $id Post/User/Comment ID
		 */
		return apply_filters( 'cac/column/actions/action_links', $actions, $this, $id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_ActionIcons( $this ) );
	}

}