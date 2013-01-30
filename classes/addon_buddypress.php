<?php

class CPAC_Addon_Buddypress {

	function __construct() {

		// add js
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// add columns
		add_filter( "cpac_get_custom_columns_wp-users", array( $this, 'add_buddypress_columns' ) );
	}

	/**
	 * Load Admin Scripts
	 *
	 * @since 2.0
	 */
	function admin_scripts() {
		wp_enqueue_script( 'cpac-custom-fields', CPAC_URL.'/assets/js/buddy-press.js', array( 'cpac-admin-columns' ), CPAC_VERSION );
	}

	/**
	 * Add BuddyPress Custom Column
	 *
	 * @param array $columns
	 * @return array Columns
	 */
	function add_buddypress_columns( $columns ) {

		$columns['column-buddypress-1'] = array(
			'label'			=> __( 'Custom BuddyPress Field', CPAC_TEXTDOMAIN ),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __( 'BuddyPress Field', CPAC_TEXTDOMAIN ),
				'class'			=> 'cpac-box-buddypress',
				'is_dynamic'	=> true // can have multiple instances

			)
		);

		return $columns;
	}
}

new CPAC_Addon_Buddypress;
