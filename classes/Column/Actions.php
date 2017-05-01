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

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_ActionIcons( $this ) );
	}

}