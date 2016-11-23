<?php
defined( 'ABSPATH' ) or die();

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_ACFPlaceholder extends AC_Column
	implements AC_Column_PlaceholderInterface {

	public function __construct() {
		$this->set_type( 'column-acf_placeholder' );
		$this->set_label( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );
		$this->set_group( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return false;
	}

	public function get_url() {
		return ac_get_site_url( 'advanced-custom-fields-columns' );
	}

}