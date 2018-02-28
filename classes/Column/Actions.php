<?php

/**
 * Base class for columns containing action links for items.
 *
 * @since 2.2.6
 */
class AC_Column_Actions extends AC_Column {

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
		if ( $this->get_setting( 'use_icons' )->get_value() ) {
			return '<span class="cpac_use_icons"></span>';
		}

		return '';
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_ActionIcons( $this ) );
	}

}