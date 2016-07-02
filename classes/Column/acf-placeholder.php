<?php
defined( 'ABSPATH' ) or die();

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class CPAC_Column_ACF_Placeholder extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-acf_placeholder';
		$this->properties['label'] = __( 'Advanced Custom Field', 'codepress-admin-columns' );
		$this->properties['group'] = __( 'Advanced Custom Fields', 'codepress-admin-columns' );
	}

	public function display_settings() {
		$this->display_settings_placeholder( ac_get_site_url( 'advanced-custom-fields-columns' ) );
	}
}