<?php

class CPAC_Columns_Links extends CPAC_Columns {

	/**
	 * Constructor
	 *
	 * @since 1.3.1
	 */
	public function __construct() {

		$this->storage_key 	= 'wp-links';
		$this->label 		= __( 'Links' );
    }

	/**
	 * Get WP default links columns.
	 *
	 * @see CPAC_Columns::get_default_columns()
	 * @since 1.3.1
	 *
	 * @return array
	 */
	function get_default() {
		// You can use this filter to add third_party columns by hooking into this.
		do_action( "cpac_before_default_columns_{$this->storage_key}" );

		// dependencies
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php' );

		// get links columns
		$columns = WP_Links_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format( $columns );

		// add sorting support to rel-tag
		if ( !empty( $columns['rel'] ) ) {
			$columns['rel']['options']['enable_sorting'] = false;
		}

		return $columns;
	}

	/**
	 * Custom links columns
	 *
	 * @see CPAC_Columns::get_custom_columns()
	 * @since 1.3.1
	 *
	 * @return array
	 */
	function get_custom() {
		$custom_columns = array(
			'column-link_id' => array (
				'label'	=> __( 'ID', CPAC_TEXTDOMAIN )
			),
			'column-description' => array (
				'label'	=> __( 'Description', CPAC_TEXTDOMAIN )
			),
			'column-image' => array(
				'label'	=> __( 'Image', CPAC_TEXTDOMAIN )
			),
			'column-target' => array(
				'label'	=> __( 'Target', CPAC_TEXTDOMAIN )
			),
			'column-owner' => array(
				'label'	=> __( 'Owner', CPAC_TEXTDOMAIN )
			),
			'column-notes' => array(
				'label'	=> __( 'Notes', CPAC_TEXTDOMAIN )
			),
			'column-rss' => array(
				'label'	=> __( 'Rss', CPAC_TEXTDOMAIN )
			),
			'column-length' => array(
				'label'	=> __( 'Length', CPAC_TEXTDOMAIN )
			),
			'column-actions' => array(
				'label'	=> __( 'Actions', CPAC_TEXTDOMAIN ),
				'options'	=> array(
					'enable_sorting' => false
				)
			)
		);

		return $custom_columns;
	}

	/**
     * Get Meta Keys
     *
	 * @see CPAC_Columns::get_meta_keys()
	 * @since 1.5.0
	 *
	 * @return array
     */
    public function get_meta_keys() {}
}