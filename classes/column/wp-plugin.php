<?php
defined( 'ABSPATH' ) or die();

class CPAC_Column_WP_Plugin extends CPAC_Column_WP_Default {

	public function __construct( $storage_model, $type, $label ) {
		parent::__construct( $storage_model, $type, $label );

		// check if original label has changed. Example WPML adds a language column, the column heading will have to display the added flag.
		if ( $this->get_property( 'hide_label' ) && $this->get_type_label() !== $this->get_option( 'label' ) ) {
			$this->set_options( 'label', $this->get_type_label() );
		}
	}

	public function init() {
		parent::init();

		$this->properties['group'] = __( 'Columns by Plugins', 'codepress-admin-columns' );
	}
}