<?php
/*
Plugin Name: 		Codepress Admin Columns
Version: 			1.4.2
Description: 		Customise the columns on the administration screens for post(types), pages, media library, comments, links and users with an easy to use drag-and-drop interface.
Author: 			Codepress
Author URI: 		http://www.codepress.nl
Plugin URI: 		http://www.codepress.nl/plugins/codepress-admin-columns/
Text Domain: 		codepress-admin-columns
Domain Path: 		/languages
License:			GPLv2

Copyright 2011  Codepress  info@codepress.nl

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'CPAC_VERSION', '1.4.2' );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

/**
 * Dependencies
 *
 * @since     1.3
 */
require_once dirname( __FILE__ ) . '/classes/sortable.php';

/**
 * Codepress Admin Columns Class
 *
 * @since     1.0
 *
 */
class Codepress_Admin_Columns 
{	
	private $post_types, 
			$slug,
			$textdomain,
			$codepress_url,
			$wordpress_url,
			$excerpt_length,
			$admin_page,
			$notice_message,
			$notice_type,
			$api_url;
	
	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{		
		$this->api_url = 'http://www.codepress.nl/';
		
		// wp is loaded
		add_action( 'wp_loaded', array( $this, 'init') );
	}
	
	/**
	 * Initialize plugin.
	 *
	 * Loading sequence is determined and intialized.
	 *
	 * @since     1.0
	 */
	public function init()
	{			
		// vars
		$this->post_types 		= $this->get_post_types();

		// set
		$this->slug				= 'codepress-admin-columns';
		$this->textdomain		= 'codepress-admin-columns';
		$this->codepress_url	= 'http://www.codepress.nl/plugins/codepress-admin-columns';
		$this->wordpress_url	= 'http://wordpress.org/tags/codepress-admin-columns';	
		
		// number of words
		$this->excerpt_length	= 20; 
		
		// translations
		load_plugin_textdomain( $this->textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu') );		
		add_action( 'admin_init', array( $this, 'register_settings') );

		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'column_styles') );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_head', array( $this, 'admin_css') );
		
		// register column headers
		add_action( 'admin_init', array( $this, 'register_columns' ) );
		
		// actions columns
		add_action( 'manage_pages_custom_column', array( $this, 'manage_posts_column_value'), 10, 2 );	
		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_column_value'), 10, 2 );
		add_filter( 'manage_users_custom_column', array( $this, 'manage_users_column_value'), 10, 3 );
		add_action( 'manage_media_custom_column', array( $this, 'manage_media_column_value'), 10, 2 );		
		add_action( 'manage_link_custom_column', array( $this, 'manage_link_column_value'), 10, 2 );		
		add_action( 'manage_comments_custom_column', array( $this, 'manage_comments_column_value'), 10, 2 );
		
		// action ajax
		add_action( 'wp_ajax_cpac_addon_activation', array( $this, 'ajax_activation'));
				
		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_requests' ), 1000 );		
		
		// filters
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);
	}	

	/**
	 * Admin Menu.
	 *
	 * Create the admin menu link for the settings page.
	 *
	 * @since     1.0
	 */
	public function settings_menu() 
	{
		$page = add_options_page(
			// Page title
			esc_html__( 'Admin Columns Settings', $this->textdomain ), 
			// Menu Title
			esc_html__( 'Admin Columns', $this->textdomain ), 
			// Capability
			'manage_options',
			// Menu slug
			$this->slug,
			// Callback
			array( $this, 'plugin_settings_page')
		);		

		// set admin page
		$this->admin_page = $page;
		
		// settings page specific styles and scripts
		add_action( "admin_print_styles-$page", array( $this, 'admin_styles') );
		add_action( "admin_print_scripts-$page", array( $this, 'admin_scripts') );
		
		// add help tabs
		add_action("load-$page", array( $this, 'help_tabs'));
	}
	
	/**
	 * Add Settings link to plugin page
	 *
	 * @since     1.0
	 */
	function add_settings_link( $links, $file ) 
	{
		if ( $file != plugin_basename( __FILE__ ))
			return $links;

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=' . $this->slug . '">' . __( 'Settings' ) . '</a>');
		return $links;
	}	
		
	/**
	 *	Register Columns	
	 *
	 *	apply_filters location in includes/screen.php
	 *
	 * 	@since     1.0
	 */
	public function register_columns()
	{	
		/** Posts */		
	 	foreach ( $this->post_types as $post_type ) {

			// register column per post type
			add_filter("manage_edit-{$post_type}_columns", array($this, 'callback_add_posts_column_headings'));
		} 
		
		/** Users */
		add_filter( "manage_users_columns", array($this, 'callback_add_users_column_headings'), 9); 
		// give higher priority, so it will load just before other plugins to prevent conflicts
		
		/** Media */
		add_filter( "manage_upload_columns", array($this, 'callback_add_media_column_headings'));
		
		/** Links */
		add_filter( "manage_link-manager_columns", array($this, 'callback_add_links_column_headings'));
		
		/** Comments */
		add_filter( "manage_edit-comments_columns", array($this, 'callback_add_comments_column_headings'));
	}
	
	/**
	 *	Callback add Posts Column
	 *
	 * 	@since     1.0
	 */
	public function callback_add_posts_column_headings($columns) 
	{
		global $post;

		return $this->add_columns_headings($post->post_type, $columns);		
	}
	
	/**
	 *	Callback add Users column
	 *
	 * 	@since     1.1
	 */
	public function callback_add_users_column_headings($columns) 
	{
		return $this->add_columns_headings('wp-users', $columns);
	}
	
	/**
	 *	Callback add Media column
	 *
	 * 	@since     1.3
	 */
	public function callback_add_media_column_headings($columns) 
	{
		return $this->add_columns_headings('wp-media', $columns);
	}
	
	/**
	 *	Callback add Links column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_links_column_headings($columns) 
	{
		return $this->add_columns_headings('wp-links', $columns);
	}
	
	/**
	 *	Callback add Comments column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_comments_column_headings($columns) 
	{
		return $this->add_columns_headings('wp-comments', $columns);
	}
	
	/**
	 *	Add managed columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_columns_headings( $type, $columns ) 
	{
		// only get stored columns.. the rest we don't need
		$db_columns	= $this->get_stored_columns($type);

		if ( !$db_columns )
			return $columns;
		
		// filter already loaded columns by plugins
		$set_columns = $this->filter_preset_columns( $type, $columns );

		// loop through columns
		foreach ( $db_columns as $id => $values ) {			
			// is active
			if ( isset($values['state']) && $values['state'] == 'on' ){				
				
				// register format
				$set_columns[$id] = $values['label'];				
			}
		}
		
		return $set_columns;
	}
	
	/**
	 * Set columns. These columns apply either for every post or set by a plugin.
	 *
	 * @since     1.0
	 */
	private function filter_preset_columns( $type, $columns ) 
	{
		$options 	= get_option('cpac_options_default');
		
		if ( !$options )
			return $columns;
		
		// we use the wp default columns for filtering...
		$stored_wp_default_columns 	= $options[$type];

		// ... the ones that are set by plugins, theme functions and such.
		$dif_columns 	= array_diff(array_keys($columns), array_keys($stored_wp_default_columns));
			
		// we add those to the columns
		$pre_columns = array();
		if ( $dif_columns ) {
			foreach ( $dif_columns as $column ) {
				$pre_columns[$column] = $columns[$column];
			}
		}
		
		return $pre_columns;
	}
	
	/**
	 *	Add managed sortable columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_managed_sortable_columns( $type, $columns ) 
	{
		$display_columns	= $this->get_merged_columns($type);
			
		if ( ! $display_columns )
			return $columns;
		
		foreach ( $display_columns as $id => $vars ) {
			if ( isset($vars['options']['sortorder']) && $vars['options']['sortorder'] == 'on' ){			
				
				// register format
				$columns[$id] = $this->sanitize_string($vars['label']);			
			}
		}	
		return $columns;
	}
	
	/**
	 * Get a list of Column options per post type
	 *
	 * @since     1.0
	 */
	private function get_column_boxes($type) 
	{	
		// merge all columns
		$display_columns 	= $this->get_merged_columns($type);
		
		// define
		$list = '';	
		
		// loop throught the active columns
		if ( $display_columns ) {
			foreach ( $display_columns as $id => $values ) {		
				
				// add items to the list
				$list .= $this->get_box($type, $id, $values);
			
			}
		}
		
		// custom field button
		$button_add_column = '';
		if ( $this->get_meta_by_type($type) )
			$button_add_column = "<a href='javacript:;' class='cpac-add-customfield-column button'>+ " . __('Add Custom Field Column', $this->textdomain) . "</a>";
		
		return "
			<div class='cpac-box'>
				<ul class='cpac-option-list'>
					{$list}			
				</ul>
				{$button_add_column}
				<div class='cpac-reorder-msg'>" . __('drag and drop to reorder', $this->textdomain) . "</div>		
			</div>
			";
	}

	/**
	 * Get merged columns
	 *
	 * @since     1.0
	 */
	protected function get_merged_columns( $type ) 
	{
		/** Comments */
		if ( $type == 'wp-comments' ) {
			$wp_default_columns = $this->get_wp_default_comments_columns();
			$wp_custom_columns  = $this->get_custom_comments_columns();
		}
		
		/** Links */
		elseif ( $type == 'wp-links' ) {
			$wp_default_columns = $this->get_wp_default_links_columns();
			$wp_custom_columns  = $this->get_custom_links_columns();
		}
		
		/** Users */
		elseif ( $type == 'wp-users' ) {
			$wp_default_columns = $this->get_wp_default_users_columns();
			$wp_custom_columns  = $this->get_custom_users_columns();
		}
		
		/** Media */
		elseif ( $type == 'wp-media' ) {
			$wp_default_columns = $this->get_wp_default_media_columns();
			$wp_custom_columns  = $this->get_custom_media_columns();
		}
		
		/** Posts */
		else {
			$wp_default_columns = $this->get_wp_default_posts_columns($type);
			$wp_custom_columns  = $this->get_custom_posts_columns($type);
		}
		
		// merge columns
		$display_columns = $this->parse_columns($wp_custom_columns, $wp_default_columns, $type);
		
		return $display_columns;		
	}
	
	/**
	 * Merge the default columns (set by WordPress) and the added custom columns (set by plugins, theme etc.)
	 *
	 * @since     1.3.3
	 */
	function parse_columns($wp_custom_columns, $wp_default_columns, $type) {
		// merge columns
		$default_columns = wp_parse_args($wp_custom_columns, $wp_default_columns);
		
		//get saved database columns
		$db_columns = $this->get_stored_columns($type);
		if ( $db_columns ) {
			
			// let's remove any unavailable columns.. such as disabled plugins
			$db_columns 	 = $this->remove_unavailable_columns($db_columns, $default_columns);
			
			// loop throught the active columns
			foreach ( $db_columns as $id => $values ) {
			
				// get column meta options from custom columns
				if ( $this->is_column_meta($id) )
					$db_columns[$id]['options'] = $wp_custom_columns['column-meta-1']['options'];			
				
				// add static options
				elseif ( isset($default_columns[$id]['options']) )
					$db_columns[$id]['options'] = $default_columns[$id]['options'];
				
				unset($default_columns[$id]);			
			}
		}	
		
		// merge all
		return wp_parse_args($db_columns, $default_columns);
	}
	
	/**
	 * Remove deactivated (plugin) columns
	 *
	 * This will remove any columns that have been stored, but are no longer available. This happends
	 * when plugins are deactivated or when they are removed from the theme functions.
	 *
	 * @since     1.2
	 */
	private function remove_unavailable_columns( array $db_columns, array $default_columns)
	{
		// check or differences
		$diff = array_diff( array_keys($db_columns), array_keys($default_columns) );
		
		if ( ! empty($diff) && is_array($diff) ) {						
			foreach ( $diff as $column_name ){				
				// make an exception for column-meta-xxx
				if ( ! $this->is_column_meta($column_name) ) {
					unset($db_columns[$column_name]);
				}
			}
		}
		
		return $db_columns;
	}
	
	/**
	 * Get checkbox
	 *
	 * @since     1.0
	 */
	private function get_box($type, $id, $values) 
	{
		$classes = array();

		// set state
		$state 	= isset($values['state']) ? $values['state'] : '';
		
		// class
		$classes[] = "cpac-box-{$id}";
		if ( $state )
			$classes[] = 'active';
		if ( ! empty($values['options']['class']) )
			$classes[] = $values['options']['class'];
		$class = implode(' ', $classes);
			
		// more box options	
		$more_options 	= $this->get_additional_box_options($type, $id, $values);
		$action 		= "<a class='cpac-action' href='#open'>open</a>";
				
		// type label
		$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';
		
		// label
		$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';
		
		// width
		$width			= isset($values['width']) ? $values['width'] : 0;
		$width_descr	= isset($values['width']) && $values['width'] > 0 ? $values['width'] . '%' : __('default', $this->textdomain);
		
		// hide box options
		$label_hidden = '';
		if ( ! empty($values['options']['hide_options']) || strpos($label, '<img') !== false ) {
			$label_hidden = ' style="display:none"';
		}
		
		$list = "
			<li class='{$class}'>
				<div class='cpac-sort-handle'></div>
				<div class='cpac-type-options'>					
					<div class='cpac-checkbox'></div>
					<input type='hidden' class='cpac-state' name='cpac_options[columns][{$type}][{$id}][state]' value='{$state}'/>
					<label class='main-label'>{$values['label']}</label>
				</div>
				<div class='cpac-meta-title'>
					{$action}
					<span>{$type_label}</span>
				</div>
				<div class='cpac-type-inside'>				
					<label for='cpac_options-{$type}-{$id}-label'{$label_hidden}>Label: </label>
					<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' id='cpac_options-{$type}-{$id}-label' value='{$label}' class='text'{$label_hidden}/>
					<label for='cpac_options-{$type}-{$id}-width'>".__('Width', $this->textdomain).":</label>			
					<input type='hidden' maxlength='4' class='input-width' name='cpac_options[columns][{$type}][{$id}][width]' id='cpac_options-{$type}-{$id}-width' value='{$width}' />
					<div class='description width-decription' title='".__('default', $this->textdomain)."'>{$width_descr}</div>
					<div class='input-width-range'></div>
					<br/>
					{$more_options}
				</div>
			</li>
		";
		
		return $list;
	}

	/**
	 * Get additional box option fields
	 *
	 * @since     1.0
	 */
	private function get_additional_box_options($type, $id, $values) 
	{
		$fields = '';
		
		// Custom Fields
		if ( $this->is_column_meta($id) )
			$fields = $this->get_box_options_customfields($type, $id, $values);
		
		return $fields;
	}

	/**
	 * Box Options: Custom Fields
	 *
	 * @since     1.0
	 */
	private function get_box_options_customfields($type, $id, $values) 
	{
		// get post meta fields	
		$fields = $this->get_meta_by_type($type);
		
		if ( empty($fields) ) 
			return false;
		
		// set meta field options
		$current = ! empty($values['field']) ? $values['field'] : '' ;
		$field_options = '';
		foreach ($fields as $field) {	
			$field_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$field,
				$field == $current? ' selected="selected"':'',
				$field
			);		
		}
		
		// set meta fieldtype options
		$currenttype = ! empty($values['field_type']) ? $values['field_type'] : '' ;
		$fieldtype_options = '';
		$fieldtypes = array(
			''				=> __('Default'),
			'image'			=> __('Image'),
			'library_id'	=> __('Media Library Icon', $this->textdomain),
			'excerpt'		=> __('Excerpt'),
			'array'			=> __('Multiple Values', $this->textdomain),
			'numeric'		=> __('Numeric', $this->textdomain),
			'date'			=> __('Date', $this->textdomain),
			'title_by_id'	=> __('Post Title (Post ID\'s)', $this->textdomain),
		);
		
		// add filter
		$fieldtypes = apply_filters('cpac-field-types', $fieldtypes );
		
		// set select options
		foreach ( $fieldtypes as $fkey => $fieldtype ) {
			$fieldtype_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$fkey,
				$fkey == $currenttype? ' selected="selected"':'',
				$fieldtype
			);
		}
		
		// before and after string
		$before = ! empty($values['before']) 	? $values['before'] : '' ;
		$after 	= ! empty($values['after']) 	? $values['after'] 	: '' ;
		
		if ( empty($field_options) )
			return false;
		
		// add remove button
		$remove = '<p class="remove-description description">'.__('This field can not be removed', $this->textdomain).'</p>';
		if ( $id != 'column-meta-1') {
			$remove = "
				<p>
					<a href='javascript:;' class='cpac-delete-custom-field-box'>".__('Remove')."</a>
				</p>
			";
		}
		
		$inside = "
			<label for='cpac-{$type}-{$id}-field'>Custom Field: </label>
			<select name='cpac_options[columns][{$type}][{$id}][field]' id='cpac-{$type}-{$id}-field'>{$field_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-field_type'>Field Type: </label>
			<select name='cpac_options[columns][{$type}][{$id}][field_type]' id='cpac-{$type}-{$id}-field_type'>{$fieldtype_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-before'>Before: </label>
			<input type='text' class='cpac-before' name='cpac_options[columns][{$type}][{$id}][before]' id='cpac-{$type}-{$id}-before' value='{$before}'/>				
			<br/>	
			<label for='cpac-{$type}-{$id}-after'>After: </label>
			<input type='text' class='cpac-after' name='cpac_options[columns][{$type}][{$id}][after]' id='cpac-{$type}-{$id}-after' value='{$after}'/>				
			<br/>		
			{$remove}
		";
		
		return $inside;
	}

	/**
	 * Get post meta fields by type; post(types) or users.
	 *
	 * @since     1.0
	 */
	private function get_meta_by_type($type = 'post') 
	{
		global $wpdb;

		/** Comments */
		if ( $type == 'wp-comments') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1";
		}
		
		/** Users */
		elseif ( $type == 'wp-users') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1";
		}
		
		/** Posts */
		else {
			$sql = $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $type);
		}
		
		// run sql
		$fields = $wpdb->get_results($sql, ARRAY_N);

		// postmeta	
		if ( $fields ) {
			$meta_fields = array();
			foreach ($fields as $field) {
				// filter out hidden meta fields
				if (substr($field[0],0,1) != "_") {
					$meta_fields[] = $field[0];
				}
			}
			return $meta_fields;
		}
		
		return false;
	}

	/**
	 * Register admin scripts
	 *
	 * @since     1.0
	 */
	public function admin_scripts() 
	{
		wp_enqueue_script( 'jquery-ui-slider' );		
		wp_enqueue_script( 'cpac-qtip2', $this->plugin_url('/assets/js/jquery.qtip.js'), array('jquery'), CPAC_VERSION );
		wp_enqueue_script( 'cpac-admin', $this->plugin_url('/assets/js/admin-column.js'), array('jquery', 'dashboard', 'jquery-ui-sortable'), CPAC_VERSION );
	}	
	
	/**
	 *	Get column types
	 *
	 * 	@since     1.1
	 */
	private function get_types() 
	{
		$types 					= $this->post_types;
		$types['wp-users'] 		= 'wp-users';
		$types['wp-media'] 		= 'wp-media';
		$types['wp-links'] 		= 'wp-links';
		$types['wp-comments'] 	= 'wp-comments';
		
		return $types;
	}
	
	/**
	 * Get post types
	 *
	 * @since     1.0
	 */
	protected function get_post_types()
	{
		$post_types = get_post_types(array(
			'_builtin' => false
		));
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';
		
		return apply_filters('cpac-get-post-types', $post_types);
	}

	/**
	 * Register admin css
	 *
	 * @since     1.0
	 */
	public function admin_styles()
	{
		wp_enqueue_style( 'jquery-ui-lightness', $this->plugin_url('/assets/ui-theme/jquery-ui-1.8.18.custom.css'), array(), CPAC_VERSION, 'all' );	
		wp_enqueue_style( 'cpac-admin', $this->plugin_url('/assets/css/admin-column.css'), array(), CPAC_VERSION, 'all' );	
	}
	
	/**
	 * Register column css
	 *
	 * @since     1.0
	 */
	public function column_styles()
	{
		wp_enqueue_style( 'cpac-columns', $this->plugin_url('/assets/css/column.css'), array(), CPAC_VERSION, 'all' );	
	}

	/**
	 * Register plugin options
	 *
	 * @since     1.0
	 */
	public function register_settings() 
	{
		// If we have no options in the database, let's add them now.
		if ( false === get_option('cpac_options') )
			add_option( 'cpac_options', array($this, 'get_default_plugin_options') );
		
		register_setting( 'cpac-settings-group', 'cpac_options', array($this, 'options_callback') );
	}	

	/**
	 * Returns the default plugin options.
	 *
	 * @since     1.0
	 */
	public function get_default_plugin_options() 
	{
		$default_plugin_options = array(		
			'post'	=> '',
			'page'	=> ''
		);
		return apply_filters( 'cpac_default_plugin_options', $default_plugin_options );
	}

	/**
	 * Optional callback.
	 *
	 * @since     1.0
	 */
	public function options_callback($options)
	{	
		return $options;
	}

	/**
	 * Handle requests.
	 *
	 * @since     1.0
	 */
	public function handle_requests() 
	{	
		// settings updated
		if ( ! empty($_REQUEST['settings-updated']) )
			$this->store_wp_default_columns();			
		
		// restore defaults 
		if ( ! empty($_REQUEST['cpac-restore-defaults']) )
			$this->restore_defaults();

	}
	
	/**
	 * Stores WP default columns
	 *
	 * This will store columns that are set by WordPress core or
	 * set by the theme for page, post(types) and user columns
	 *
	 * @since     1.2
	 */
	private function store_wp_default_columns() 
	{	
		// stores the default columns that are set by WP or set in the theme.
		$wp_default_columns = array();
		
		// Posts
		foreach ( $this->post_types as $post_type ) {
			$wp_default_columns[$post_type] = $this->get_wp_default_posts_columns($post_type);
		}
		
		// Users
		$wp_default_columns['wp-users'] = $this->get_wp_default_users_columns();
		
		// Media
		$wp_default_columns['wp-media'] = $this->get_wp_default_media_columns();
		
		// Links
		$wp_default_columns['wp-links'] = $this->get_wp_default_links_columns();
		
		// Comments
		$wp_default_columns['wp-comments'] = $this->get_wp_default_comments_columns();		
		
		update_option( 'cpac_options_default', $wp_default_columns );
	}

	/**
	 * Restore defaults
	 *
	 * @since     1.0
	 */
	private function restore_defaults() 
	{	
		delete_option( 'cpac_options' );
		delete_option( 'cpac_options_default' );		
	}

	/**
	 * Returns excerpt
	 *
	 * @since     1.0
	 */
	private function get_post_excerpt($post_id) 
	{
		global $post;
			
		$save_post 	= $post;
		$post 		= get_post($post_id);
		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;
		
		$output = $this->get_shortened_string($excerpt, $this->excerpt_length );	
		
		return $output;
	}

	/**
	 * Returns shortened string
	 *
	 * @since     1.0
	 */
	private function get_shortened_string($string = '', $num_words = 55, $more = null) 
	{
		if (!$string)
			return false;
		
		return wp_trim_words( $string, $num_words, $more );
	}

	/**
	 * Manage custom column for Post Types.
	 *
	 * @since     1.0
	 */
	public function manage_posts_column_value($column_name, $post_id) 
	{
		$type = $column_name;

		// Check for taxonomies, such as column-taxonomy-[taxname]	
		if ( strpos($type, 'column-taxonomy-') !== false )
			$type = 'column-taxonomy';
		
		// Check for custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-post-meta';
		
		// Hook 
		do_action('cpac-manage-posts-column', $type, $column_name, $post_id);
	
		// Switch Types
		$result = '';
		switch ($type) :			
			
			// Post ID
			case "column-postid" :
				$result = $post_id;
				break;
			
			// Excerpt
			case "column-excerpt" :
				$result = $this->get_post_excerpt($post_id);
				break;
			
			// Featured Image
			case "column-featured_image" :
				if ( has_post_thumbnail($post_id) )
					$result = get_the_post_thumbnail($post_id, array(80,80));			
				break;
				
			// Sticky Post
			case "column-sticky" :
				if ( is_sticky($post_id) )					
					$result = $this->get_asset_image('checkmark.png');
				break;
			
			// Order
			case "column-order" :
				$result = get_post_field('menu_order', $post_id);			
				break;
				
			// Post Formats
			case "column-post_formats" :
				$result = get_post_format($post_id);			
				break;
			
			// Page template
			case "column-page-template" :
				// file name
				$page_template 	= get_post_meta($post_id, '_wp_page_template', true);			

				// get template nice name
				$result = array_search($page_template, get_page_templates());			
				break;
			
			// Slug
			case "column-page-slug" :
				$result = get_post($post_id)->post_name;
				break;
			
			// Slug
			case "column-word-count" :
				$result = str_word_count( $this->strip_trim( get_post($post_id)->post_content ) );
				break;
			
			// Taxonomy
			case "column-taxonomy" :
				$tax 	= str_replace('column-taxonomy-', '', $column_name);
				$tags 	= get_the_terms($post_id, $tax);
				$tarr 	= array();
				
				// for post formats we will display standard instead of empty
				if ( $tax == 'post_format' && empty($tags) ) {
					$result = __('Standard');
				}
				
				// add name with link
				elseif ( !empty($tags) ) {	
					$post_type = get_post_type($post_id);
					foreach($tags as $tag) {
						// sanatize title
						if ( isset($tag->term_id) ) {
							$tax_title 	= esc_html(sanitize_term_field('name', $tag->name, $tag->term_id, $tag->taxonomy, 'edit'));
							$tarr[] 	= "<a href='edit.php?post_type={$post_type}&{$tag->taxonomy}={$tag->slug}'>{$tax_title}</a>";
						}
					}
					$result = implode(', ', $tarr);
				}			
				break;
			
			// Custom Field
			case "column-post-meta" :
				$result = $this->get_column_value_custom_field($post_id, $column_name, 'post');		
				break;
			
			// Attachment
			case "column-attachment" :
				$result = $this->get_column_value_attachments($post_id);
				break;
				
			// Attachment count
			case "column-attachment-count" :
				$result = count($this->get_attachment_ids($post_id));
				break;
				
			// Roles
			case "column-roles" :
				$user_id 	= get_post($post_id)->post_author;
				$userdata 	= get_userdata( $user_id );
				if ( !empty($userdata->roles[0]) )
					echo implode(', ',$userdata->roles);
				break;
			
			// Post status
			case "column-status" :
				$p 		= get_post($post_id);
				$result = $p->post_status;
				if ( $result == 'future')
					$result = $result . " <p class='description'>" . date_i18n( get_option('date_format') . ' ' . get_option('time_format') , strtotime($p->post_date) ) . "</p>";
				break;
				
			// Post comment status
			case "column-comment-status" :
				$p 		= get_post($post_id);
				$result = $this->get_asset_image('no.png', $p->comment_status);
				if ( $p->comment_status == 'open' )
					$result = $this->get_asset_image('checkmark.png', $p->comment_status);
				break;
				
			// Post ping status
			case "column-ping-status" :
				$p 		= get_post($post_id);
				$result = $this->get_asset_image('no.png', $p->ping_status);
				if ( $p->ping_status == 'open' )
					$result = $this->get_asset_image('checkmark.png', $p->ping_status);
				break;
			
			// Post actions ( delete, edit etc. )
			case "column-actions" :
				$result = $this->get_column_value_actions($post_id, 'posts');
				break;
			
			default :
				$result = '';
						
		endswitch;
		
		echo $result;	
	}		
	
	/**
	 * Manage custom column for Users.
	 *
	 * @since     1.1
	 */
	public function manage_users_column_value( $value, $column_name, $user_id )
	{
		$type = $column_name;
		
		$userdata = get_userdata( $user_id );

		if ( ! $userdata )
			return false;
		
		// Check for user custom fields: column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-user-meta';
			
		// Check for post count: column-user_postcount-[posttype]
		if ( $this->get_posttype_by_postcount_column($type) )
			$type = 'column-user_postcount';
		
		// Hook 
		do_action('cpac-manage-users-column', $type, $column_name, $user_id);
		
		$result = '';
		switch ($type) :			
			
			// user id
			case "column-user_id" :
				$result = $user_id;
				break;
			
			// first name
			case "column-nickname" :
				$result = $userdata->nickname;
				break;
			
			// first name
			case "column-first_name" :
				$result = $userdata->first_name;
				break;
				
			// last name
			case "column-last_name" :
				$result = $userdata->last_name;
				break;
			
			// user url
			case "column-user_url" :
				$result = $userdata->user_url;
				break;
				
			// user registration date
			case "column-user_registered" :
				$result = $userdata->user_registered;
				break;
				
			// user description
			case "column-user_description" :
				$result = $this->get_shortened_string( get_the_author_meta('user_description', $user_id), $this->excerpt_length );
				break;
				
			// user description
			case "column-user_postcount" :
				$post_type 	= $this->get_posttype_by_postcount_column($column_name);
				
				// get post count
				$count 		= $this->get_post_count( $post_type, $user_id );
				
				// set result
				$result 	= $count > 0 ? "<a href='edit.php?post_type={$post_type}&author={$user_id}'>{$count}</a>" : (string) $count;
				break; 
			
			// user actions
			case "column-actions" :
				$result = $this->get_column_value_actions($user_id, 'users');
				break;
			
			// user meta data ( custom field )
			case "column-user-meta" :
				$result = $this->get_column_value_custom_field($user_id, $column_name, 'user');
				break;
			
			default :
				$result = '';
				
		endswitch;
		
		return $result;
	}
	
	/**
	 * Manage custom column for Media.
	 *
	 * @since     1.3
	 */
	public function manage_media_column_value( $column_name, $media_id )
	{
		$type 	= $column_name;
		
		$meta 	= wp_get_attachment_metadata($media_id);
		$p 		= get_post($media_id);
		
		// Hook 
		do_action('cpac-manage-media-column', $type, $column_name, $media_id);
		
		$result = '';
		switch ($type) :			
			
			// media id
			case "column-mediaid" :
				$result = $media_id;
				break;			
			
			// dimensions
			case "column-dimensions" :
				if ( !empty($meta['width']) &&  !empty($meta['height']) )
					$result = "{$meta['width']} x {$meta['height']}";
				break;
			
			// width
			case "column-width" :
				$result	= !empty($meta['width']) ? $meta['width'] : '';
				break;
			
			// height
			case "column-height" :
				$result	= !empty($meta['height']) ? $meta['height'] : '';
				break;
			
			// description
			case "column-description" :
				$result	= $p->post_content;
				break;
				
			// caption
			case "column-caption" :
				$result	= $p->post_excerpt;
				break;
				
			// alternate text
			case "column-alternate_text" :
				$alt 	= get_post_meta($media_id, '_wp_attachment_image_alt', true);
				$result = $this->strip_trim($alt);
				break;
				
			// mime type
			case "column-mime_type" :				
				$result = $p->post_mime_type;
				break;
			
			// file name
			case "column-file_name" :
				$file 		= wp_get_attachment_url($p->ID);
				$filename 	= basename($file);
				$result 	= "<a title='{$filename}' href='{$file}'>{$filename}</a>";
				break;
				
			// file paths
			case "column-file_paths" :
				$sizes 		= get_intermediate_image_sizes();
				$url 		= wp_get_attachment_url($p->ID);
				$filename 	= basename($url);				
				$paths[] 	= "<a title='{$filename}' href='{$url}'>" . __('original', $this->textdomain) . "</a>";
				if ( $sizes ) {
					foreach ( $sizes as $size ) {
						$src 	= wp_get_attachment_image_src( $media_id, $size );						
						if (!empty($src[0])) {
							$filename = basename($src[0]);
							$paths[] = "<a title='{$filename}' href='{$src[0]}'>{$size}</a>";
						}
					}
				}				
				$result = implode('<span class="cpac-divider"></span>', $paths);
				break;
			
			default :
				$result = '';
			
		endswitch;
		
		echo $result;
	}
	
	/**
	 * Manage custom column for Links
	 *
	 * @since     1.3.1
	 */
	public function manage_link_column_value( $column_name, $link_id )
	{
		$type = $column_name;
		
		// links object... called bookmark
		$bookmark = get_bookmark($link_id);

		// Hook 
		do_action('cpac-manage-link-column', $type, $column_name, $link_id);
		
		$result = '';
		switch ($type) :			
			
			// link id
			case "column-link_id" :
				$result = $link_id;
				break;
			
			// description
			case "column-description" :
				$result = $bookmark->link_description;
				break;
			
			// target
			case "column-target" :
				$result = $bookmark->link_target;
				break;
			
			// notes
			case "column-notes" :
				$result = $this->get_shortened_string($bookmark->link_notes, $this->excerpt_length);
				break;
			
			// rss
			case "column-rss" :
				$result 	= $this->get_shorten_url($bookmark->link_rss);
				break;
				
			// image
			case "column-image" :
				$result = $this->get_thumbnail($bookmark->link_image);
				break;
				
			// name length
			case "column-length" :				
				$result = strlen($bookmark->link_name);
				break;
				
			// owner
			case "column-owner" :
				$result = $bookmark->link_owner;
				
				// add user link
				$userdata = get_userdata( $bookmark->link_owner );				
				if (!empty($userdata->data)) {
					$result = $userdata->data->user_nicename;
					//$result = "<a href='user-edit.php?user_id={$bookmark->link_owner}'>{$result}</a>";
				}
				break;
			
			default :
				$result = '';
			
		endswitch;
		
		echo $result;
	}
	
	/**
	 * Manage custom column for Comments
	 *
	 * @since     1.3.1
	 */
	public function manage_comments_column_value( $column_name, $comment_id )
	{
		$type = $column_name;
		
		// comments object
		$comment = get_comment($comment_id);
		
		// Check for custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-comment-meta';
		
		// Hook 
		do_action('cpac-manage-comments-column', $type, $column_name, $comment_id);
		
		$result = '';
		switch ($type) :			
			
			// comment id
			case "column-comment_id" :
				$result = $comment_id;
				break;
			
			// author
			case "column-author_author" :
				$result = $comment->comment_author;
				break;
				
			// avatar
			case "column-author_avatar" :
				$result = get_avatar( $comment, 80 );				
				break;
				
			// url
			case "column-author_url" :				
				$result	= $this->get_shorten_url($comment->comment_author_url);				
				break;
				
			// ip
			case "column-author_ip" :
				$result = $comment->comment_author_IP;
				break;
				
			// email
			case "column-author_email" :
				$result = $comment->comment_author_email;
				break;
				
			// parent
			case "column-reply_to" :
				if ( $comment->comment_approved ) {				
					$parent 		= get_comment( $comment->comment_parent );
					$parent_link 	= esc_url( get_comment_link( $comment->comment_parent ) );
					$name 			= get_comment_author( $parent->comment_ID );
					$result 		= sprintf( '<a href="%1$s">%2$s</a>', $parent_link, $name );
				}
				break;	
			
			// approved
			case "column-approved" :
				$result = $this->get_asset_image('no.png');
				if ( $comment->comment_approved )
					$result = $this->get_asset_image('checkmark.png');
				break;
			
			// date
			case "column-date" :
				$comment_url = esc_url( get_comment_link( $comment_id ) );
				$result 	 = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ), 
					$comment_url,
					$this->get_date($comment->comment_date),
					$this->get_time($comment->comment_date)
				);
				$result 	 = "<div class='submitted-on'>{$result}</div>";
				break;
			
			// date GMT
			case "column-date_gmt" :
				$comment_url = esc_url( get_comment_link( $comment_id ) );
				$result 	 = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ), 
					$comment_url,
					$this->get_date($comment->comment_date_gmt),
					$this->get_time($comment->comment_date_gmt)
				);
				$result 	 = "<div class='submitted-on'>{$result}</div>";
				break;
				
			// custom field
			case "column-comment-meta" :
				$result = $this->get_column_value_custom_field($comment_id, $column_name, 'comment');		
				break;
				
			// agent
			case "column-agent" :
				$result = $comment->comment_agent;		
				break;	
				
			// excerpt
			case "column-excerpt" :
				$comment 	= get_comment($comment_id);
				$result 	= $this->get_shortened_string($comment->comment_content, $this->excerpt_length);
				break;	
			
			default :
				$result = '';
			
		endswitch;
		
		echo $result;
	}

	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.0
	 */
	private function get_column_value_attachments( $post_id ) 
	{
		$result 	 	= '';
		$attachment_ids = $this->get_attachment_ids($post_id);
		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attach_id ) {
				if ( wp_get_attachment_image($attach_id) )
					$result .= wp_get_attachment_image( $attach_id, array(80,80), true );
			}
		}
		return $result;
	}
	
	/**
	 *	Get column value of post actions
	 *
	 *	This part has been taken from the following classes
	 *	Posts List Table
	 *	
	 *
	 * 	@since     1.4.2
	 */
	private function get_column_value_actions( $id, $type = 'posts' ) 
	{	
		$actions = array();
		
		/** Posts */
		if ( $type == 'posts') {
			$post_id			= $id;
			$post 				= get_post($post_id);
			$title 				= _draft_or_post_title();
			$post_type_object 	= get_post_type_object( $post->post_type );
			$can_edit_post 		= current_user_can( $post_type_object->cap->edit_post, $post->ID );
			
			if ( $can_edit_post && 'trash' != $post->post_status ) {
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
			if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
				if ( 'trash' == $post->post_status )
					$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
				elseif ( EMPTY_TRASH_DAYS )
					$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
					$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
			}
			if ( $post_type_object->public ) {
				if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
					if ( $can_edit_post )
						$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
				} elseif ( 'trash' != $post->post_status ) {
					$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
				}
			}
		}
		
		/** Users */
		elseif ( $type == 'users' ) {
			
			$user_object = new WP_User( $id );
			$screen 	 = get_current_screen();
			
			if ( 'site-users-network' == $screen->id )
				$url = "site-users.php?id={$this->site_id}&amp;";
			else
				$url = 'users.php?';
			
			if ( get_current_user_id() == $user_object->ID ) {
				$edit_link = 'profile.php';
			} else {
				$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( stripslashes( $_SERVER['REQUEST_URI'] ) ), "user-edit.php?user_id=$user_object->ID" ) );
			}
			
			if ( current_user_can( 'edit_user',  $user_object->ID ) ) {
				$edit = "<strong><a href=\"$edit_link\">$user_object->user_login</a></strong><br />";
				$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			} else {
				$edit = "<strong>$user_object->user_login</strong><br />";
			}

			if ( !is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) )
				$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . "</a>";
			if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) )
				$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url."action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . "</a>";
		}
		
		return implode(' | ', $actions);
	}
	
	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.2.1
	 */
	protected function get_attachment_ids( $post_id ) 
	{
		return get_posts(array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
	}
	
	/**
	 *	Get post count
	 *
	 * 	@since     1.3.1
	 */
	protected function get_post_count( $post_type, $user_id )
	{
		if ( ! post_type_exists($post_type) || ! get_userdata($user_id) )
			return false;
			
		$user_posts = get_posts(array(
			'post_type'		=> $post_type,
			'numberposts' 	=> -1,
			'author' 		=> $user_id,
			'post_status' 	=> 'publish'
		));
		return count($user_posts);
	}
	
	/**
	 *	Get image from assets folder
	 *
	 * 	@since     1.3.1
	 */
	protected function get_asset_image($name = '', $title = '')
	{
		if ( $name )
			return sprintf("<img alt='' src='%s' title='%s'/>", $this->plugin_url("assets/images/{$name}"), $title);
	}
	
	/**
	 *	Shorten URL
	 *
	 * 	@since     1.3.1
	 */
	protected function get_shorten_url($url = '')
	{
		if ( !$url )
			return false;
			
		// shorten url
		$short_url 	= url_shorten( $url );
		
		return "<a title='{$url}' href='{$url}'>{$short_url}</a>";		
	}
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.0
	 */	
	protected function get_column_value_custom_field($object_id, $column_name, $meta_type = 'post') 
	{
		/** Users */
		if ( $meta_type == 'user' ) {
			$type = 'wp-users';
		}
		
		/** Posts */
		else {
			$type 	= get_post_type($object_id);
		}
		
		// get column
		$columns 	= $this->get_stored_columns($type);
		
		// inputs
		$field	 	= isset($columns[$column_name]['field']) 	  ? $columns[$column_name]['field'] 		: '';
		$fieldtype	= isset($columns[$column_name]['field_type']) ? $columns[$column_name]['field_type'] 	: '';
		$before 	= isset($columns[$column_name]['before']) 	  ? $columns[$column_name]['before'] 		: '';
		$after 		= isset($columns[$column_name]['after']) 	  ? $columns[$column_name]['after'] 		: '';
		
		// Get meta field value
		$meta 	 	= get_metadata($meta_type, $object_id, $field, true);

		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array($meta) ) || is_array($meta) ) {			
			$meta 	= get_metadata($meta_type, $object_id, $field, true);
			$meta 	= $this->recursive_implode(', ', $meta);
		}
		
		// make sure there are no serialized arrays or empty meta data
		if ( empty($meta) || !is_string($meta) )	
			return false;
					
		// handles each field type differently..
		switch ($fieldtype) :			
		
			// Image
			case "image" :				
				$meta = $this->get_thumbnail($meta);
				break;
				
			// Media Library ID
			case "library_id" :
				$meta = $this->get_media_thumbnails($meta);				
				break;
			
			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string($meta, $this->excerpt_length);
				break;
			
			// Date
			case "date" :
				$meta = $this->get_date($meta);
				break;
			
			// Title
			case "title_by_id" :
				$titles = $this->get_custom_field_value_title($meta);
				if ( $titles )
					$meta = $titles;
				break;
			
		endswitch;		
		
		// add before and after string
		$meta = "{$before}{$meta}{$after}";
		
		return $meta;
	}
	
	/**
	 *	Get custom field value 'Title by ID'
	 *
	 * 	@since     1.3
	 */
	private function get_custom_field_value_title($meta) 
	{
		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace(' ','', $meta) );
		
		// var
		$ids = $titles = array();
		
		// check for multiple id's
		if ( strpos($meta, ',') !== false )
			$ids = explode(',',$meta);			
		elseif ( is_numeric($meta) )
			$ids[] = $meta;			
		
		// display title with link
		if ( $ids && is_array($ids) ) {
			foreach ( $ids as $id ) {				
				$title = is_numeric($id) ? get_the_title($id) : '';
				$link  = get_edit_post_link($id);
				if ( $title )
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
			}
		}
		
		return implode('<span class="cpac-divider"></span>', $titles);
	}
	
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.2
	 */
	private function get_user_column_value_custom_field($user_id, $id) 
	{		
		$columns 	= $this->get_stored_columns('wp-users');
		
		// inputs
		$field	 	= isset($columns[$id]['field']) 	 ? $columns[$id]['field'] 		: '';
		$fieldtype	= isset($columns[$id]['field_type']) ? $columns[$id]['field_type'] 	: '';
		$before 	= isset($columns[$id]['before']) 	 ? $columns[$id]['before'] 		: '';
		$after 		= isset($columns[$id]['after']) 	 ? $columns[$id]['after'] 		: '';
		
		// Get meta field value
		$meta 	 	= get_user_meta($user_id, $field, true);
		
		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array($meta) ) || is_array($meta) ) {			
			$meta 	= get_user_meta($user_id, $field);
			$meta 	= $this->recursive_implode(', ', $meta);
		}
		
		// make sure there are no serialized arrays or empty meta data
		if ( empty($meta) || !is_string($meta) )	
			return false;
					
		// handles each field type differently..
		switch ($fieldtype) :			
		
			// Image
			case "image" :				
				$meta = $this->get_thumbnail($meta);
				break;
				
			// Media Library ID
			case "library_id" :
				$meta = $this->get_media_thumbnails($meta);
				break;
			
			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string($meta, $this->excerpt_length);
				break;
								
		endswitch;		
		
		// add before and after string
		$meta = "{$before}{$meta}{$after}";
		
		return $meta;
	}

	/**
	 *	Implode for multi dimensional array
	 *
	 * 	@since     1.0
	 */
	private function recursive_implode( $glue, $pieces ) 
	{
		foreach( $pieces as $r_pieces )	{
			if( is_array( $r_pieces ) ) {
				$retVal[] = $this->recursive_implode( $glue, $r_pieces );
			}
			else {
				$retVal[] = $r_pieces;
			}
		}
		if ( isset($retVal) && is_array($retVal) )
			return implode( $glue, $retVal );
		
		return false;
	}			

	/**
	 * 	Get WP default supported admin columns per post type.
	 *
	 * 	@since     1.0
	 */
	private function get_wp_default_posts_columns($post_type = 'post') 
	{
		// load dependencies
		
		// deprecated as of wp3.3
		if ( file_exists(ABSPATH . 'wp-admin/includes/template.php') )
			require_once(ABSPATH . 'wp-admin/includes/template.php');
			
		// introduced since wp3.3
		if ( file_exists(ABSPATH . 'wp-admin/includes/screen.php') )
			require_once(ABSPATH . 'wp-admin/includes/screen.php');
			
		// used for getting columns
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');
		
		// some plugins depend on settings the $_GET['post_type'] variable such as ALL in One SEO
		$_GET['post_type'] = $post_type;
		
		// for 3rd party plugin support we will call load-edit.php so all the 
		// additional columns that are set by them will be avaible for us
		do_action('load-edit.php');
		
		// we need to change the current screen
		global $current_screen;
		$org_current_screen = $current_screen;
		
		// overwrite current_screen global with our post type of choose...
		$current_screen->post_type = $post_type;
		
		// ...so we can get its columns
		$columns = WP_Posts_List_Table::get_columns();
		
		if ( empty ( $columns ) )
			return false;
		
		// change to uniform format
		$posts_columns = $this->get_uniform_format($columns);		

		// reset current screen
		$current_screen = $org_current_screen;
		
		return $posts_columns;
	}
	
	/**
	 * 	Get WP default users columns per post type.
	 *
	 * 	@since     1.1
	 */
	private function get_wp_default_users_columns()
	{
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php');
		
		// turn off site users
		$this->is_site_users = false;
		
		// get users columns
		$columns = WP_Users_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_users_columns($columns);

		return apply_filters('cpac-default-users-columns', $columns);
	}
	
	/**
	 * 	Add Sorting to WP default Users columns
	 *
	 * 	@since     1.4
	 */
	private function set_sorting_to_default_users_columns($columns)
	{
		// Comment
		if ( !empty($columns['role']) ) {
			$columns['role']['options']['sortorder'] = 'on';
		}
		return $columns;
	}
	
	/**
	 * 	Get WP default media columns.
	 *
	 * 	@since     1.2.1
	 */
	private function get_wp_default_media_columns()
	{
		// could use _get_list_table('WP_Media_List_Table') ?
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');
		
		global $current_screen;
		$org_current_screen = $current_screen;
		
		// overwrite current_screen global with our media id...
		$current_screen->id = 'upload';
		
		// init media class
		$wp_media = new WP_Media_List_Table;
		
		// get media columns		
		$columns = $wp_media->get_columns();
		
		// reset current screen
		$current_screen = $org_current_screen;
		
		// change to uniform format
		return $this->get_uniform_format($columns);
	}
	
	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	private function get_wp_default_links_columns()
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
		
		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_links_columns($columns);
		
		return apply_filters('cpac-default-links-columns', $columns);
	}
	
	/**
	 * 	Add Sorting to WP default links columns
	 *
	 * 	@since     1.4
	 */
	private function set_sorting_to_default_links_columns($columns)
	{
		// Relationship
		if ( !empty($columns['rel']) ) {
			$columns['rel']['options']['sortorder'] = 'on';
		}
		return $columns;
	}
	
	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	private function get_wp_default_comments_columns()
	{
		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php');
		
		global $current_screen;
		$org_current_screen = $current_screen;
		
		// overwrite current_screen global with our media id...
		$current_screen->id = 'edit-comments';
		
		// init table object
		$wp_comment = new WP_Comments_List_Table;		
		
		// get comments
		$columns = $wp_comment->get_columns();
		
		// reset current screen
		$current_screen = $org_current_screen;
		
		// change to uniform format
		$columns = $this->get_uniform_format($columns);
		
		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_comments_columns($columns);
		
		return apply_filters('cpac-default-comments-columns', $columns);
	}
	
	/**
	 * 	Add Sorting to WP default comments columns
	 *
	 * 	@since     1.4
	 */
	private function set_sorting_to_default_comments_columns($columns)
	{
		// Comment
		if ( !empty($columns['comment']) ) {
			$columns['comment']['options']['sortorder'] = 'on';
		}
		return $columns;
	}

	/**
	 * Build uniform format for all columns
	 *
	 * @since     1.0
	 */
	private function get_uniform_format($columns) 
	{
		// we remove the checkbox column as an option... 
		if ( isset($columns['cb']) )
			unset($columns['cb']);
		
		// change to uniform format
		$uniform_columns = array();
		foreach ( (array) $columns as $id => $label ) {
			$hide_options 	= false;
			$type_label 	= $label;
			
			// comment exception				
			if ( strpos( $label, 'comment-grey-bubble.png') ) {
				$type_label 	= __('Comments', $this->textdomain);
				$hide_options 	= true;
			}
			
			// user icon excerption
			if ( $id == 'icon' ) {
				$type_label 	= __('Icon', $this->textdomain);
			}
			
			$uniform_colums[$id] = array(
				'label'			=> $label,
				'state'			=> 'on',
				'options'		=> array(
					'type_label' 	=> $type_label,
					'hide_options'	=> $hide_options,
					'class'			=> 'cpac-box-wp-native',
				)
			);
		}
		return $uniform_colums;
	}
	
	/**
	 * Custom posts columns
	 *
	 * @since     1.0
	 */
	private function get_custom_posts_columns($post_type) 
	{
		$custom_columns = array(
			'column-featured_image' => array(
				'label'	=> __('Featured Image', $this->textdomain)
			),
			'column-excerpt' => array(
				'label'	=> __('Excerpt', $this->textdomain)
			),
			'column-order' => array(
				'label'	=> __('Page Order', $this->textdomain)
			),
			'column-post_formats' => array(
				'label'	=> __('Post Format', $this->textdomain)
			),
			'column-postid' => array(
				'label'	=> __('ID', $this->textdomain)
			),
			'column-page-slug' => array(
				'label'	=> __('Slug', $this->textdomain)
			),
			'column-attachment' => array(
				'label'	=> __('Attachment', $this->textdomain)
			),
			'column-attachment-count' => array(
				'label'	=> __('No. of Attachments', $this->textdomain)
			),
			'column-roles' => array(
				'label'	=> __('Roles', $this->textdomain)
			),
			'column-status' => array(
				'label'	=> __('Status', $this->textdomain)
			),
			'column-comment-status' => array(
				'label'	=> __('Comment status', $this->textdomain)
			),
			'column-ping-status' => array(
				'label'	=> __('Ping status', $this->textdomain)
			),
			'column-actions' => array(
				'label'	=> __('Actions', $this->textdomain),
				'options'	=> array(
					'sortorder'	=> false
				)
			)
		);
		
		// Word count support
		if ( post_type_supports($post_type, 'editor') ) {
			$custom_columns['column-word-count'] = array(
				'label'	=> __('Word count', $this->textdomain)
			);
		}
		
		// Sticky support
		if ( $post_type == 'post' ) {		
			$custom_columns['column-sticky'] = array(
				'label'			=> __('Sticky', $this->textdomain)
			);
		}
		
		// Order support
		if ( post_type_supports($post_type, 'page-attributes') ) {
			$custom_columns['column-order'] = array(
				'label'			=> __('Page Order', $this->textdomain),				
				'options'		=> array(
					'type_label' 	=> __('Order', $this->textdomain)
				)			
			);
		}
		
		// Page Template
		if ( $post_type == 'page' ) { 
			$custom_columns['column-page-template'] = array(
				'label'	=> __('Page Template', $this->textdomain)
			);	
		}
		
		// Post Formats
		if ( post_type_supports($post_type, 'post-formats') ) {
			$custom_columns['column-post_formats'] = array(
				'label'	=> __('Post Format', $this->textdomain)
			);
		}
		
		// Taxonomy support
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		if ( $taxonomies ) {
			foreach ( $taxonomies as $tax_slug => $tax ) {
				if ( $tax_slug != 'post_tag' && $tax_slug != 'category' && $tax_slug != 'post_format' ) {
					$custom_columns['column-taxonomy-'.$tax->name] = array(
						'label'			=> $tax->label,
						'options'		=> array(
							'type_label'	=> __('Taxonomy', $this->textdomain)
						)
					);				
				}
			}
		}
		
		// Custom Field support
		if ( $this->get_meta_by_type($post_type) ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', $this->textdomain),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Field', $this->textdomain),
					'class'			=> 'cpac-box-metafield'
				)			
			);
		}	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-posts-columns', $custom_columns);
	}
	
	/**
	 * Custom users columns
	 *
	 * @since     1.1
	 */
	private function get_custom_users_columns() 
	{
		$custom_columns = array(
			'column-user_id' => array(
				'label'	=> __('User ID', $this->textdomain)
			),
			'column-nickname' => array(
				'label'	=> __('Nickname', $this->textdomain)
			),
			'column-first_name' => array(
				'label'	=> __('First name', $this->textdomain)
			),
			'column-last_name' => array(
				'label'	=> __('Last name', $this->textdomain)
			),
			'column-user_url' => array(
				'label'	=> __('Url', $this->textdomain)
			),
			'column-user_registered' => array(
				'label'	=> __('Registered', $this->textdomain)
			),
			'column-user_description' => array(
				'label'	=> __('Description', $this->textdomain)
			),
			'column-actions' => array(
				'label'	=> __('Actions', $this->textdomain),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
		);
		
		// User total number of posts
		if ($this->get_post_types()) {
			foreach ( $this->get_post_types() as $post_type ) {
				$label = $this->get_plural_name($post_type);
				$custom_columns['column-user_postcount-'.$post_type] = array(
					'label'			=> __( sprintf('No. of %s',$label), $this->textdomain),
					'options'		=> array(
						'type_label'	=> __('Postcount', $this->textdomain)
					)
				);
			}
		}
		
		// Custom Field support
		$custom_columns['column-meta-1'] = array(
			'label'			=> __('Custom Field', $this->textdomain),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __('Field', $this->textdomain),
				'class'			=> 'cpac-box-metafield'
			)
		);	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-users-columns', $custom_columns);
	}
	
	/**
	 * Custom media columns
	 *
	 * @since     1.3
	 */
	private function get_custom_media_columns() 
	{
		$custom_columns = array(
			'column-mediaid' => array(
				'label'	=> __('ID', $this->textdomain)
			),
			'column-mime_type' => array(
				'label'	=> __('Mime type', $this->textdomain)
			),
			'column-file_name' => array(
				'label'	=> __('File name', $this->textdomain)
			),
			'column-dimensions' => array(
				'label'	=> __('Dimensions', $this->textdomain)
			),
			'column-height' => array(
				'label'	=> __('Height', $this->textdomain)
			),
			'column-width' => array(
				'label'	=> __('Width', $this->textdomain)
			),
			'column-caption' => array(
				'label'	=> __('Caption', $this->textdomain)
			),
			'column-description' => array(
				'label'	=> __('Description', $this->textdomain)
			),
			'column-alternate_text' => array(
				'label'	=> __('Alt', $this->textdomain)
			),
			'column-file_paths' => array(
				'label'	=> __('Upload paths', $this->textdomain),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
		);		
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-media-columns', $custom_columns);
	}
	
	/**
	 * Custom links columns
	 *
	 * @since     1.3.1
	 */
	private function get_custom_links_columns() 
	{
		$custom_columns = array(
			'column-link_id' => array (
				'label'	=> __('ID', $this->textdomain)
			),
			'column-description' => array (
				'label'	=> __('Description', $this->textdomain)
			),
			'column-image' => array(
				'label'	=> __('Image', $this->textdomain)
			),
			'column-target' => array(
				'label'	=> __('Target', $this->textdomain)
			),
			'column-owner' => array(
				'label'	=> __('Owner', $this->textdomain)
			),
			'column-notes' => array(
				'label'	=> __('Notes', $this->textdomain)
			),
			'column-rss' => array(
				'label'	=> __('Rss', $this->textdomain)
			),
			'column-length' => array(
				'label'	=> __('Length', $this->textdomain)
			)			
		);	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-links-columns', $custom_columns);
	}
	
	/**
	 * Custom comments columns
	 *
	 * @since     1.3.1
	 */
	private function get_custom_comments_columns() 
	{
		$custom_columns = array(
			'column-comment_id' => array(
				'label'	=> __('ID', $this->textdomain)
			),
			'column-author_author' => array(
				'label'	=> __('Author Name', $this->textdomain)
			),
			'column-author_avatar' => array(
				'label'	=> __('Avatar', $this->textdomain)
			),
			'column-author_url' => array(
				'label'	=> __('Author url', $this->textdomain)
			),
			'column-author_ip' => array(
				'label'	=> __('Author IP', $this->textdomain)
			),
			'column-author_email' => array(
				'label'	=> __('Author email', $this->textdomain)
			),
			'column-reply_to' => array(
				'label'			=> __('In Reply To', $this->textdomain),
				
				// options
				'options'		=> array(					
					'sortorder'		=> false
				)
			),
			'column-approved' => array(
				'label'	=> __('Approved', $this->textdomain)
			),
			'column-date' => array(
				'label'	=> __('Date', $this->textdomain)
			),
			'column-date_gmt' => array(
				'label'	=> __('Date GMT', $this->textdomain)
			),
			'column-agent' => array(
				'label'	=> __('Agent', $this->textdomain)
			),
			'column-excerpt' => array(
				'label'	=> __('Excerpt', $this->textdomain)
			)
		);		
		
		// Custom Field support
		if ( $this->get_meta_by_type('wp-comments') ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', $this->textdomain),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Field', $this->textdomain),
					'class'			=> 'cpac-box-metafield',
					'sortorder'		=> false,
				)
			);
		}		
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-comments-columns', $custom_columns);
	}
	
	/**
	 * Parse defaults
	 *
	 * @since     1.1
	 */
	private function parse_defaults($columns) 
	{
		// default arguments
		$defaults = array(	
			
			// stored values
			'label'			=> '',
			'state' 		=> '',
			'width' 		=> '',
			
			// static values
			'options'		=> array(				
				'type_label'	=> __('Custom', $this->textdomain),
				'hide_options'	=> false,
				'class'			=> 'cpac-box-custom',
				'sortorder'		=> 'on',
			)
		);
		
		// parse args
		foreach ( $columns as $k => $column ) {
			$c[$k] = wp_parse_args( $column, $defaults);
			
			// parse options args
			if ( isset($column['options']) )
				$c[$k]['options'] = wp_parse_args( $column['options'], $defaults['options']);
				
			// set type label
			if ( empty($column['options']['type_label']) && !empty($column['label']) )
				$c[$k]['options']['type_label']	= $column['label'];
		}
		
		return $c;
	}

	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	protected function get_stored_columns($type)
	{ 
		// get plugin options
		$options 		= get_option('cpac_options');

		// get saved columns
		if ( isset($options['columns'][$type]) )
			return $options['columns'][$type];
		
		return false;
	}

	/**
	 * Post Type Menu
	 *
	 * @since     1.0
	 */
	private function get_menu() 
	{
		// set
		$menu 	= '';
		$count 	= 1;
		
		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';
			
		// loop
		foreach ( $this->get_types() as $type ) {
			$label 		 = $this->get_singular_name($type);
			$clean_label = $this->sanitize_string($type);
			
			// divider
			$divider 	= $count++ == 1 ? '' : ' | ';
			
			// current		
			$current = '';
			if ( $this->is_menu_type_current($type) )
				$current = ' class="current"';
			
			// menu list
			$menu .= "
				<li>{$divider}<a{$current} href='#cpac-box-{$clean_label}'>{$label}</a></li>
			";
		}
		
		// settings url
		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current': '';
		
		// options button
		$options_btn = "<a href='#cpac-box-plugin_settings' class='cpac-settings-link{$class_current_settings}'>".__('Addons')."</a>";
		//$options_btn = '';
		
		return "
		<div class='cpac-menu'>
			<ul class='subsubsub'>
				{$menu}
			</ul>
			{$options_btn}
		</div>
		";
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since     1.0
	 */
	private function is_menu_type_current( $type ) 
	{	
		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';
		
		// get label
		$clean_label = $this->sanitize_string($type);
		
		// get first element from post-types
		$first 		= array_shift( array_values($this->post_types) );
		
		// display the page that was being viewed before saving
		if ( $referer ) {
			if ( $referer == 'cpac-box-'.$clean_label ) {
				return true;
			}
		
		// settings page has not yet been saved
		} elseif ( $first == $type  ) {
			return true;
		}
		
		return false;	
	}

	/**
	 * Get singular name of post type
	 *
	 * @since     1.0
	 */
	private function get_singular_name( $type ) 
	{
		// Links
		if ( $type == 'wp-links' )
			$label = 'Links';
			
		// Comments
		elseif ( $type == 'wp-comments' )
			$label = 'Comments';
			
		// Users
		elseif ( $type == 'wp-users' )
			$label = 'Users';
		
		// Media
		elseif ( $type == 'wp-media' )
			$label = 'Media Library';
		
		// Posts
		else {
			$posttype_obj 	= get_post_type_object($type);
			$label 			= $posttype_obj->labels->singular_name;
		}
		
		return $label;
	}
	
	/**
	 * Get plural name of post type
	 *
	 * @since     1.3.1
	 */
	private function get_plural_name( $type ) 
	{
		$posttype_obj = get_post_type_object($type);
		if ( $posttype_obj )
			return $posttype_obj->labels->name;
		
		return false;
	}
	
	/**
	 * Get screen link to overview screen
	 *
	 * @since     1.3.1
	 */
	private function get_type_screen_link( $type ) 
	{		
		// Links
		if ( $type == 'wp-comments' )
			$link = get_admin_url() . 'edit-comments.php';
			
			// Links
		if ( $type == 'wp-links' )
			$link = get_admin_url() . 'link-manager.php';
		
		// Users
		if ( $type == 'wp-users' )
			$link = get_admin_url() . 'users.php';
		
		// Media
		elseif ( $type == 'wp-media' )
			$link = get_admin_url() . 'upload.php';
		
		// Posts
		else	
			$link = get_admin_url() . "edit.php?post_type={$type}";		
				
		return $link;
	}

	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since     1.0
	 */
	protected function sanitize_string($string) 
	{	
		$string = esc_url($string);
		$string = str_replace('http://','', $string);
		$string = str_replace('https://','', $string);
		
		return $string;
	}
	
	/**
	 * Get plugin url.
	 *
	 * @since     1.0
	 */
	private function plugin_url( $file = '' ) 
	{		
		return plugins_url($file, __FILE__);
	}
	
	/**
	 * Checks if column-meta key exists
	 *
	 * @since     1.0
	 */
	protected function is_column_meta( $id = '' ) 
	{
		if ( strpos($id, 'column-meta-') !== false )
			return true;
		
		return false;
	}
	
	/**
	 * Get the posttype from columnname
	 *
	 * @since     1.3.1
	 */
	protected function get_posttype_by_postcount_column( $id = '' ) 
	{
		if ( strpos($id, 'column-user_postcount-') !== false )			
			return str_replace('column-user_postcount-', '', $id);
				
		return false;
	}
	
	/**
	 * Get a thumbnail
	 *
	 * @since     1.0
	 */
	private function get_thumbnail( $image = '' ) 
	{		
		if ( empty($image) )
			return false;
		
		// get correct image path
		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $image);
		
		// resize image		
		if ( file_exists($image_path) && $this->is_image($image_path) ) {
			$resized = image_resize( $image_path, 80, 80, true);
			
			if ( ! is_wp_error( $resized ) ) {
				$image  = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized);
				
				return "<img src='{$image}' alt='' width='80' height='80' />";
			}
			
			return $resized->get_error_message();
		}
		
		return false;
	}
	
	/**
	 * Get a thumbnail
	 *
	 * @since     1.3.1
	 */
	private function get_media_thumbnails($meta) 
	{
		$meta = $this->strip_trim( str_replace(' ','', $meta) );
		
		// split media ids
		$media_ids = array($meta);
		if ( strpos($meta, ',') !== false )			
			$media_ids = explode(',', $meta);
		
		// check if media exists
		$thumbs = '';
		foreach ( $media_ids as $media_id )
			if ( is_numeric($media_id) )
				$thumbs .= wp_get_attachment_url($media_id) ? "<span class='cpac-column-value-image'>".wp_get_attachment_image( $media_id, array(80,80), true )."</span>" : '';
		
		return $thumbs;		
	}
	
	/**
	 * Checks an URL for image extension
	 *
	 * @since     1.2
	 */
	private function is_image($url) 
	{
		$validExt  	= array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
		$ext    	= strrchr($url, '.');
		
		return in_array($ext, $validExt);
	}	
	
	/**
	 * Admin body class
	 *
	 * @since     1.4
	 */
	function admin_class() 
	{		
		global $current_screen;
				
		// we dont need the 'edit-' part
		$screen = str_replace('edit-', '', $current_screen->id);
		
		// media library exception
		if ( $current_screen->base == 'upload' && $current_screen->id == 'upload' ) {
			$screen = 'media';
		}
		
		// link exception
		if ( $current_screen->base == 'link-manager' && $current_screen->id == 'link-manager' ) {
			$screen = 'links';
		}

		// loop the available types
		foreach ( $this->get_types() as $type => $label ) {			
			
			// match against screen or wp-screen
			if ( $type == $screen || $type == "wp-{$screen}" )
				return "cp-{$type}";
		}
		return false;
	}
	
	/**
	 * Admin CSS for Column width
	 *
	 * @since     1.4
	 */
	function admin_css() 
	{	
		$css = '';
		
		// loop throug the available types...
		foreach ( $this->get_types() as $type ) {
			$cols = $this->get_stored_columns($type);			
			if ( $cols ) {
			
				// loop through each available column...
				foreach ( $cols as $col_name => $col ) {
				
					// and check for stored width and add it to the css
					if (!empty($col['width']) && is_numeric($col['width']) && $col['width'] > 0 ) {
						$css .= ".cp-{$type} .wrap table th.column-{$col_name} { width: {$col['width']}% !important; }";
					}
				}
			}
		}
		
		echo "<style type='text/css'>{$css}</style>";
	}

	/**
	 * Unlocks
	 *
	 * @since     1.3
	 */
	protected function is_unlocked($type)
	{
		return preg_match('/^[a-f0-9]{40}$/i', $this->get_license_key($type));
	}	
	
	/**
	 * Check license key with API
	 *
	 * @since     1.3.3
	 */
	private function check_remote_key($type, $key)
	{	
		if ( empty($type) || empty($key) )
			return false;
		
		// check key with remote API		
 		$response = wp_remote_post( $this->api_url, array(			
			'body'	=> array(
				'api'	=> 'addon',
				'key'	=> $key,
				'type'	=> $type				
			)
		));

		// license will be valid in case of WP error or succes
		if ( is_wp_error($response) || ( isset($response['body']) && json_decode($response['body']) == 'valid' ) )
			return true;
	
		return false;
	}
	
	/**
	 * Set masked license key
	 *
	 * @since     1.3.1
	 */
	private function get_masked_license_key($type) 
	{
		return '**************************'.substr( $this->get_license_key($type), -4 );		
	}
	
	/**
	 * Ajax activation
	 *
	 * @since     1.3.1
	 */
	public function ajax_activation()
	{
		// keys
		$key 	= $_POST['key'];
		$type 	= $_POST['type'];
		
		// update key
		if ( $key == 'remove' ) {
			$this->remove_license_key($type);
		}
			
		// set license key
		elseif ( $this->check_remote_key($type, $key) ) {
		
			// set key
			$this->set_license_key($type, $key);
			
			// returned masked key
			echo json_encode( $this->get_masked_license_key($type) );
		}

		exit;
	}
	
	/**
	 * Get license key
	 *
	 * @since     1.3
	 */
	private function get_license_key($type)
	{
		return get_option("cpac_{$type}_ac");
	}
	
	/**
	 * Set license key
	 *
	 * @since     1.3
	 */
	private function set_license_key($type, $key)
	{			
		update_option( "cpac_{$type}_ac", $key);
	}
	
	/**
	 * Remove license key
	 *
	 * @since     1.3.1
	 */
	private function remove_license_key($type)
	{
		delete_option( "cpac_{$type}_ac" );
		delete_transient("cpac_{$type}_trnsnt");
	}
	
	/**
	 * Strip tags and trim
	 *
	 * @since     1.3
	 */
	protected function strip_trim($string) 
	{
		return trim(strip_tags($string));
	}
	
	/**
	 * Get date
	 *
	 * @since     1.3.1
	 */
	protected function get_date($date) 
	{
		if ( ! $date )
			return false;
			
		if ( ! is_numeric($date) )
			$date = strtotime($date);
			
		return date_i18n( get_option('date_format'), $date );
	}
	
	/**
	 * Get time
	 *
	 * @since     1.3.1
	 */
	protected function get_time($date) 
	{
		if ( ! $date )
			return false;
			
		if ( ! is_numeric($date) )
			$date = strtotime($date);
		
		return date_i18n( get_option('time_format'), $date );
	}	
	
	/**
	 * Admin notices
	 *
	 * @since     1.3.1
	 */
	private function admin_notice($message = '', $type = 'updated')
	{	
		$this->notice_message 	= $message;
		$this->notice_type 		= $type; // updated, error

		add_action('admin_notices', array( $this, 'callback_admin_notice' ) );		
	}
	
	/**
	 * Output Notice
	 *
	 * @since     1.3.1
	 */
	public function callback_admin_notice()
	{
		echo "<div class='{$this->notice_type}' id='message'><p>{$this->notice_message}</p></div>";
	}
	
	/**
	 * Add help tabs
	 *
	 * @since     1.3
	 */
	public function help_tabs($page) 
	{		
		$screen = get_current_screen();

		if ( $screen->id != $this->admin_page || ! method_exists($screen,'add_help_tab') )
			return;
		
		$admin_url = get_admin_url();
		
		// add help content
		$tabs = array(
			array(
				'title'		=> 'Overview',
				'content'	=> "
					<h5>Codepress Admin Columns</h5>
					<p>
						This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.
					</p>	
					
				"
			),
			array(
				'title'		=> 'Basics',
				'content'	=> "
					<h5>Show / Hide</h5>
					<p>
						You can switch columns on or off by cliking on the checkbox. This will show or hide each column heading.
					</p>
					<h5>Change order</h5>
					<p>
						By dragging the columns you can change the order which they will appear in.
					</p>
					<h5>Change label</h5>
					<p>
						By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.
					</p>
					<h5>Change coluimn width</h5>
					<p>
						By clicking on the triangle you will see the column options. By using the draggable slider yo can set the width of the columns in percentages.
					</p>
				"
			),
			array(
				'title'		=> 'Custom Field',
				'content'	=> "
					<h5>'Custom Field' column</h5>
					<p>
						The custom field colum uses the custom fields from posts and users. There are 8 types which you can set.
					</p>
					<ul>
						<li><strong>Default</strong><br/>Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.</li>
						<li><strong>Image</strong><br/>Value: should only contain an image URL.</li>
						<li><strong>Media Library Icon</strong><br/>Value: should only contain Attachment IDs ( seperated by ',' ).</li>
						<li><strong>Excerpt</strong><br/>Value: This will show the first 20 words of the Post content.</li>
						<li><strong>Multiple Values</strong><br/>Value: should be an array. This will flatten any ( multi dimensional ) array.</li>
						<li><strong>Numeric</strong><br/>Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.</li>
						<li><strong>Date</strong><br/>Value: Can be unix time stamp of date format as described in the <a href='http://codex.wordpress.org/Formatting_Date_and_Time'>Codex</a>. You can change the outputted date format at the <a href='{$admin_url}options-general.php'>general settings</a> page.</li>
						<li><strong>Post Titles</strong><br/>Value: can be one or more Post ID's (seperated by ',').</li>
					</ul>
				"
			)			
		);
		
		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab(array(				
				'id'		=> 'cpac-tab-'.$k, 	// unique id
				'title'		=> $tab['title'],	// label
				'content'	=> $tab['content'], // body
			));
		}
	}
	
	/**
	 * Activation settings
	 *
	 * @since     1.3.1
	 */
	private function activation_settings() 
	{
		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current' : ' hidden'; '';
		
		/** Sortable */
		$masked_key 				= '';
		$class_sortorder_activate 	= '';
		$class_sortorder_deactivate = ' hidden';
		
		// is unlocked
		if ( $this->is_unlocked('sortable') ) {
			$masked_key 	 = $this->get_masked_license_key('sortable');
			$class_sortorder_activate = ' hidden';
			$class_sortorder_deactivate = '';
		}
		
		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='button-primary alignright' target='_blank'>".__('find out more', $this->textdomain)." &raquo</a>";
		
		// info box
		$sortable_tooltip = "
			<p>".__('This will make all of the new columns support sorting', $this->textdomain).".</p>
			<p>".__('By default WordPress let\'s you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong>', $this->textdomain)."</p>
			<p>".__('Perfect for sorting your articles, media files, comments, links and users', $this->textdomain).".</p>			
			<img src='" . $this->plugin_url('/assets/images/addon_sortable_1.png') . "' alt='' />
			{$find_out_more}
		";
		
		// markup
		$sortable = "
		<tr id='cpac-activation-sortable' class='last'>
			<td class='activation_type'>
				<span>" . __('Sortorder', $this->textdomain) . "</span>
				<div class='cpac-tooltip hidden'>
					<div class='qtip_title'>" . __('Sortorder', $this->textdomain) . "</div>
					<div class='qtip_content'>
						<p>" . __($sortable_tooltip, $this->textdomain) . "</p>
					</div>
				</div>
			</td>
			<td class='activation_status'>
				<div class='activate{$class_sortorder_activate}'>
					" . __('Inactive', $this->textdomain) . "
				</div>
				<div class='deactivate{$class_sortorder_deactivate}'>
					" . __('Active', $this->textdomain) . "
				</div>
			</td>
			<td class='activation_code'>
				<div class='activate{$class_sortorder_activate}'>
					<input type='text' value='" . __('Fill in your activation code', $this->textdomain) . "' name='cpac-sortable-key'>
					<a href='javascript:;' class='button'>" . __('Activate', $this->textdomain) . "<span></span></a>
				</div>
				<div class='deactivate{$class_sortorder_deactivate}'>
					<span class='masked_key'>{$masked_key}</span>
					<a href='javascript:;' class='button'>" . __('Deactivate', $this->textdomain) . "<span></span></a>
				</div>
				<div class='activation-error-msg'></div>
			</td>
			<td class='activation_more'>{$find_out_more}</td>
		</tr><!-- #cpac-activation-sortable -->
		";
		
		// settings
		$row = "
		<tr id='cpac-box-plugin_settings' valign='top' class='cpac-box-row {$class_current_settings}'>
			<td colspan='2'>
				<table class='nopadding'>
					<tr class='last'>
						<td>
							<h2>".__('Activate Add-ons', $this->textdomain)."</h2>
							<p>".__('Add-ons can be unlocked by purchasing a license key. Each key can be used on multiple sites', $this->textdomain)." <a target='_blank' href='{$this->codepress_url}/sortorder-addon/'>Visit the Plugin Store</a>.</p>
							<table class='widefat addons'>
								<thead>
									<tr>
										<th class='activation_type'>".__('Addon', $this->textdomain)."</th>
										<th class='activation_status'>".__('Status', $this->textdomain)."</th>
										<th class='activation_code'>".__('Activation Code', $this->textdomain)."</th>
										<th class='activation_more'></th>
									</tr>
								</thead>
								<tbody>
									{$sortable}
								</tbody>					
							</table>
							<div class='addon-translation-string hidden'>
								<span class='tstring-fill-in'>" . __('Enter your activation code', $this->textdomain) . "</span>
								<span class='tstring-unrecognised'>" . __('Activation code unrecognised', $this->textdomain) . "</span>
							</div>
						</td>
					</tr>
					<!--
					<tr class='last'>
						<td colspan='2'>
							<h2>Options</h2>
							<ul class='cpac-options'>
								<li>
									<div class='cpac-option-label'>Thumbnail size</div>
									<div class='cpac-option-inputs'>										
										<input type='text' id='thumbnail_size_w' class='small-text' name='cpac_options[settings][thumb_width]' value='80'/>
										<label for='thumbnail_size_w'>Width</label>
										<br/>										
										<input type='text' id='thumbnail_size_h' class='small-text' name='cpac_options[settings][thumb_height]' value='80'/>
										<label for='thumbnail_size_h'>Height</label>
									</div>
								</li>
								<li>
									<div class='cpac-option-label'>Excerpt length</div>
									<div class='cpac-option-inputs'>										
										
										<input type='text' id='excerpt_length' class='small-text' name='cpac_options[settings][excerpt_length]' value='15'/>
										<label for='excerpt_length'>Number of words</label>
									</div>
								</li>
							</ul>						
						</td>
					</tr>
					-->
				</table>
			</td>
		</tr><!-- #cpac-box-plugin_settings -->
		";
		
		return $row;
	}
	
	/**
	 * Settings Page Template.
	 *
	 * This function in conjunction with others usei the WordPress
	 * Settings API to create a settings page where users can adjust
	 * the behaviour of this plugin. 
	 *
	 * @since     1.0
	 */
	public function plugin_settings_page() 
	{

		// loop through post types
		$rows = '';
		foreach ( $this->get_types() as $type ) {
			
			// post type label
			$label = $this->get_singular_name($type);			
			
			// screen link
			$screen_link = '';
			//$screen_link = $this->get_type_screen_link($type);
			//$screen_link = "<a href='{$screen_link}' class='go-to-screen'>" . sprintf( __('go to %s screen'), strtolower($label) ) . "</a>";	
			
			// id
			$id = $this->sanitize_string($type); 
			
			// build draggable boxes
			$boxes = $this->get_column_boxes($type);

			// class
			$class = $this->is_menu_type_current($type) ? ' current' : ' hidden';
			
			$rows .= "
				<tr id='cpac-box-{$id}' valign='top' class='cpac-box-row{$class}'>
					<th class='cpac_post_type' scope='row'>
						{$label}{$screen_link}
					</th>
					<td>
						<h3 class='cpac_post_type hidden'>{$label}</h3>
						{$boxes}						
					</td>
				</tr>
			";
		}
		
		// Activation 
		$activation_settings = $this->activation_settings();
		
		// Post Type Menu
		$menu = $this->get_menu();
		
		// Help screen message
		$help_text = '';
		if ( version_compare( get_bloginfo('version'), '3.2', '>' ) )
			$help_text = '<p>'.__('You will find a short overview at the <strong>Help</strong> section in the top-right screen.', $this->textdomain).'</p>';
		
		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='alignright green' target='_blank'>".__('find out more', $this->textdomain)." &raquo</a>";
		
	?>
		<div id="cpac" class="wrap">
			<?php screen_icon($this->slug) ?>
			<h2><?php _e('Codepress Admin Columns', $this->textdomain); ?></h2>
			<?php echo $menu ?>
			
			<div class="postbox-container cpac-col-right">
				<div class="metabox-holder">	
					<div class="meta-box-sortables">						
						
						<div id="addons-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Addons', $this->textdomain) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('By default WordPress let\'s you only sort by title, date, comments and author.', $this->textdomain) ?></p>
								<p><?php _e('Make <strong>all columns</strong> of <strong>all types</strong> support sorting &#8212; with the sorting addon.', $this->textdomain) ?></p>
								<?php echo $find_out_more ?>
							</div>
						</div><!-- addons-cpac-settings -->
						
						<div id="likethisplugin-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Like this plugin?', $this->textdomain) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('Why not do any or all of the following', $this->textdomain) ?>:</p>
								<ul>
									<li><a href="<?php echo $this->codepress_url ?>/"><?php _e('Link to it so other folks can find out about it.', $this->textdomain) ?></a></li>
									<li><a href="<?php echo $this->wordpress_url ?>"><?php _e('Give it a 5 star rating on WordPress.org.', $this->textdomain) ?></a></li>
									<li class="donate_link"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZDZRSYLQ4Z76J"><?php _e('Donate a token of your appreciation.', $this->textdomain) ?></a></li>
								</ul>								
							</div>
						</div><!-- likethisplugin-cpac-settings -->
						
						<div id="side-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Need support?', $this->textdomain) ?></span>
							</h3>
							<div class="inside">								
								<?php echo $help_text ?>
								<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a> or send me an email %s.', $this->textdomain), 'http://wordpress.org/tags/codepress-admin-columns', '<a href="mailto:info@codepress.nl">info@codepress.nl</a>' );?></p>
								<p><?php printf(__("If you're sure you've found a bug, or have a feature request, please <a href='%s'>submit your feedback</a>.", $this->textdomain), "{$this->codepress_url}/feedback");?></p>
							</div>
						</div><!-- side-cpac-settings -->
					
					</div>
				</div>
			</div><!-- .postbox-container -->
			
			<div class="postbox-container cpac-col-left">
				<div class="metabox-holder">	
					<div class="meta-box-sortables">
					
						<div id="general-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Admin Columns', $this->textdomain ); ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="options.php">
								
								<?php settings_fields( 'cpac-settings-group' ); ?>
								
								<table class="form-table">
									<!-- columns -->
									<?php echo $rows; ?>								
									
									<!-- activation -->
									<?php echo $activation_settings; ?>
									
									<tr class="bottom" valign="top">
										<th scope="row"></th>
										<td>
											<p class="submit">
												<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
											</p>
										</td>
									</tr>				
								</table>			
								</form>						
							</div>						
						</div><!-- general-settings -->
						
						<div id="restore-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Restore defaults', $this->textdomain) ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="">					
									<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e('Restore default settings', $this->textdomain ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", $this->textdomain); ?>');" />
								</form>
								<p class="description"><?php _e('This will delete all column settings and restore the default settings.', $this->textdomain); ?></p>
							</div>
						</div><!-- restore-cpac-settings -->
					
					</div>
				</div>
			</div><!-- .postbox-container -->			
		</div>
	<?php
	}
}

/**
 * Init Class Codepress_Admin_Columns
 *
 * @since     1.0
 */
new Codepress_Admin_Columns();


/**
 * Init Class Codepress_Sortable_Columns
 *
 * @since     1.3
 */
new Codepress_Sortable_Columns();

?>