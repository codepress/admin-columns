<?php
defined( 'ABSPATH' ) or die();

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_ACFPlaceholder extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-acf_placeholder';
		$this->properties['label'] = __( 'Advanced Custom Field', 'codepress-admin-columns' );
		$this->properties['group'] = __( 'Advanced Custom Fields', 'codepress-admin-columns' );
	}

	public function display_settings() {
		$this->settings()->placeholder_field( array( 'label' => $this->get_label, 'type' => $this->get_type(), 'url' => ac_get_site_url( 'advanced-custom-fields-columns' ) ) );
	}

}