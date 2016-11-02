<?php
defined( 'ABSPATH' ) or die();

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_ACFPlaceholder extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-acf_placeholder' );
		$this->set_label( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );
		$this->set_group( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) );

		// TODO: add placeholder message
	}

	public function get_value( $id ) {
		return false;
	}

	public function display_settings() {
		$this->field_settings->placeholder( array( 'label' => $this->get_label, 'type' => $this->get_type(), 'url' => ac_get_site_url( 'advanced-custom-fields-columns' ) ) );
	}

}