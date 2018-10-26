<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all list screens supporting WordPress' default way of handling meta data.
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 * @since 1.0
 */
class CustomField extends Column\Meta {

	public function __construct() {
		$this->set_type( 'column-meta' );
		$this->set_label( __( 'Custom Field', 'codepress-admin-columns' ) );
		$this->set_group( 'custom_field' );
	}

	public function get_meta_key() {
		return $this->get_setting( 'custom_field' )->get_value();
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\CustomField( $this ) )
		     ->add_setting( new Settings\Column\BeforeAfter( $this ) );

		if ( ! ac_is_pro_active() ) {
			$this->add_setting( new Settings\Column\Pro\Sorting( $this ) )
			     ->add_setting( new Settings\Column\Pro\Editing( $this ) )
			     ->add_setting( new Settings\Column\Pro\Filtering( $this ) )
			     ->add_setting( new Settings\Column\Pro\Export( $this ) );
		}
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field_type() {
		return $this->get_setting( 'field_type' )->get_value();
	}

	/**
	 * @since 3.2.1
	 */
	public function get_field() {
		return $this->get_meta_key();
	}

}