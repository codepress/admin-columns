<?php

class cpac_columns_links extends cpac_columns
{
	public function __construct()
    {
		$this->type = 'wp-links';
    }
	
	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	function get_default_columns()
	{
		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php');
		
		// get links columns
		$columns = WP_Links_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format($columns);
		
		// add sorting support to rel-tag
		if ( !empty($columns['rel']) ) {
			$columns['rel']['options']['sortorder'] = 'on';
		}
		
		return apply_filters('cpac-default-links-columns', $columns);
	}
	
	/**
	 * Custom links columns
	 *
	 * @since     1.3.1
	 */
	function get_custom_columns() 
	{
		$custom_columns = array(
			'column-link_id' => array (
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-description' => array (
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-image' => array(
				'label'	=> __('Image', CPAC_TEXTDOMAIN)
			),
			'column-target' => array(
				'label'	=> __('Target', CPAC_TEXTDOMAIN)
			),
			'column-owner' => array(
				'label'	=> __('Owner', CPAC_TEXTDOMAIN)
			),
			'column-notes' => array(
				'label'	=> __('Notes', CPAC_TEXTDOMAIN)
			),
			'column-rss' => array(
				'label'	=> __('Rss', CPAC_TEXTDOMAIN)
			),
			'column-length' => array(
				'label'	=> __('Length', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			)			
		);	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-links-columns', $custom_columns);
	}
	
	/**
     * Get Meta Keys
     * 
	 * @since 1.5
     */
    public function get_meta_keys() {}
	
	/**
	 * Get Label
	 *
	 * @since 1.5
	 */
	function get_label()
	{
		return __('Links');
	}
}