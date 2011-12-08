<?php
/*
Plugin Name: 		Codepress Admin Columns
Version: 			1.2.1
Description: 		This plugin makes it easy to Manage Custom Columns for your Posts, Pages and Custom Post Type Screens.
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

define( 'CPAC_VERSION', '1.2.1' );

/**
 * Init Class
 *
 * @since     1.0
 */
new Codepress_Admin_Columns();

/**
 * Advanced Admin Columns Class
 *
 * @since     1.0
 *
 */
class Codepress_Admin_Columns 
{	
	private $post_types, 
			$slug,
			$textdomain,
			$excerpt_length;
	
	/**
	 * Construct
	 *
	 * @since     1.0
	 */
	function __construct()
	{	
		add_action( 'wp_loaded', array( &$this, 'init') );		
	}
	
	/**
	 * Initialize plugin.
	 *
	 * Loading sequence is determined and intialized.
	 *
	 * @since     1.0
	 */
	function init()
	{	
		// vars
		$this->post_types 		= $this->get_post_types();

		// set
		$this->slug				= 'codepress-admin-columns';
		$this->textdomain		= 'codepress-admin-columns';
		$this->excerpt_length	= 100;
		
		// translations
		load_plugin_textdomain( $this->textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// actions
		add_action( 'admin_menu', array( &$this, 'settings_menu') );		
		add_action( 'admin_init', array( &$this, 'register_settings') );
		add_action( 'admin_init', array( &$this, 'register_columns' ) );		
		add_action( 'manage_pages_custom_column', array( &$this, 'manage_posts_column_value'), 10, 2 );	
		add_action( 'manage_posts_custom_column', array( &$this, 'manage_posts_column_value'), 10, 2 );
		add_action( 'manage_users_custom_column', array( &$this, 'manage_users_column_value'), 10, 3 );
		add_action( 'admin_print_styles' , array( &$this, 'column_styles') );
		
		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( &$this, 'handle_requests' ), 1000 ); 
		
		// filters		
		add_filter( 'request', array( &$this, 'handle_requests_orderby_column') );		
		add_filter( 'plugin_action_links',  array( &$this, 'add_settings_link'), 1, 2);		
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
			array( &$this, 'plugin_settings_page')
		);		
				
		// settings page specific styles and scripts
		add_action( "admin_print_styles-$page", array( &$this, 'admin_styles') );
		add_action( "admin_print_scripts-$page", array( &$this, 'admin_scripts') );
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
	 * 	@since     1.0
	 */
	public function register_columns()
	{	
		/** Posts */
	 	foreach ( $this->post_types as $post_type ) {

			// register column per post type
			add_filter("manage_edit-{$post_type}_columns", array(&$this, 'callback_add_posts_column'));
					
			// register column as sortable
			add_filter( "manage_edit-{$post_type}_sortable_columns", array(&$this, 'callback_add_sortable_posts_column'));
		} 
		
		/** Users */
		add_filter( "manage_users_columns", array(&$this, 'callback_add_users_column'));
		add_filter( "manage_users_sortable_columns", array(&$this, 'callback_add_sortable_users_column'));		
	}
	
	/**
	 *	Callback add Posts Column
	 *
	 * 	@since     1.0
	 */
	public function callback_add_posts_column($columns) 
	{
		global $post_type;

		return $this->add_managed_columns($post_type, $columns);		
	}
	
	/**
	 *	Callback add Users column
	 *
	 * 	@since     1.1
	 */
	public function callback_add_users_column($columns) 
	{
		return $this->add_managed_columns('wp-users', $columns);
	}
	
	/**
	 *	Add managed columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_managed_columns( $type = 'post', $columns ) 
	{
		// only get stored columns.. the rest we don't need
		$db_columns	= $this->get_stored_columns($type);
		
		if ( !$db_columns )
			return $columns;
		
		// filter already loaded columns by plugins
		$set_columns = $this->filter_preset_columns($columns, $type);
				
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
	 *	Callback add Posts sortable column
	 *
	 * 	@since     1.0
	 */
	public function callback_add_sortable_posts_column($columns) 
	{
		global $post_type;
		
		return $this->add_managed_sortable_columns($post_type, $columns);		
	}

	/**
	 *	Callback add Users sortable column
	 *
	 * 	@since     1.1
	 */
	public function callback_add_sortable_users_column($columns) 
	{
		return $this->add_managed_sortable_columns('wp-users', $columns);
	}
	
	/**
	 *	Add managed sortable columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_managed_sortable_columns( $type = 'post', $columns ) 
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
			$button_add_column = "<a href='javacript:;' class='cpac-add-customfield-column button'>+ " . __('Add Custom Field Column') . "</a>";
		
		return "
			<div class='cpac-box'>
				<ul class='cpac-option-list'>
					{$list}			
				</ul>
				{$button_add_column}
				<div class='cpac-reorder-msg'></div>		
			</div>
			";
	}

	/**
	 * Get merged columns
	 *
	 * @since     1.0
	 */
	private function get_merged_columns( $type ) 
	{	
		//get saved database columns
		$db_columns 		= $this->get_stored_columns($type);

		/** Users */
		if ( $type == 'wp-users' ) {
			$wp_default_columns = $this->get_wp_default_users_columns();
			$wp_custom_columns  = $this->get_custom_users_columns();
		}
		
		/** Posts */
		else {
			$wp_default_columns = $this->get_wp_default_posts_columns($type);
			$wp_custom_columns  = $this->get_custom_posts_columns($type);
		}
			
		// merge columns
		$default_columns = wp_parse_args($wp_custom_columns, $wp_default_columns);
				
		// loop throught the active columns
		if ( $db_columns ) {
			
			// let's remove any unavailable columns.. such as disabled plugins
			$db_columns 	 = $this->remove_unavailable_columns($db_columns, $default_columns);
			
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
		$display_columns = wp_parse_args($db_columns, $default_columns);		
		
		return $display_columns;		
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
		
		// hide box options
		if ( ! empty($values['options']['hide_options']) || strpos($label, '<img') !== false ) {
			$action = $more_options = '';
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
					<label for='cpac_options[columns][{$type}][{$id}][label]'>Label: </label>
					<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' value='{$label}' class='text'/>
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
	private function get_additional_box_options($post_type, $id, $values) 
	{
		$fields = '';
		
		// Custom Fields	
		if ( $this->is_column_meta($id) )
			$fields .= $this->get_box_options_customfields($post_type, $id, $values);
		
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
			<label for='cpac_options[columns][{$type}][{$id}][field]'>Custom Field: </label>
			<select name='cpac_options[columns][{$type}][{$id}][field]'>{$field_options}</select>
			<br/>
			<label for='cpac_options[columns][{$type}][{$id}][field_type]'>Field Type: </label>
			<select name='cpac_options[columns][{$type}][{$id}][field_type]'>{$fieldtype_options}</select>
			<br/>
			<label for='cpac_options[columns][{$type}][{$id}][before]'>Before: </label>
			<input type='text' class='cpac-before' name='cpac_options[columns][{$type}][{$id}][before]' value='{$before}'/>				
			<br/>	
			<label for='cpac_options[columns][{$type}][{$id}][before]'>After: </label>
			<input type='text' class='cpac-after' name='cpac_options[columns][{$type}][{$id}][after]' value='{$after}'/>				
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
		
		/** Users */
		if ( $type == 'wp-users') {
			$sql 	= 'SELECT DISTINCT meta_key	FROM '.$wpdb->usermeta.' ORDER BY 1';
		}
		
		/** Posts */
		else {
			$sql 	= 'SELECT DISTINCT meta_key	FROM '.$wpdb->postmeta.' pm	JOIN '.$wpdb->posts.' p	ON pm.post_id = p.ID WHERE p.post_type = "' . mysql_real_escape_string($type) . '" ORDER BY 1';
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
		wp_enqueue_script( 'cpac-admin', $this->plugin_url('/assets/js/admin-column.js'), array('jquery', 'dashboard', 'jquery-ui-sortable'), CPAC_VERSION );	
	}	
	
	/**
	 *	Get column types
	 *
	 * 	@since     1.1
	 */
	private function get_types() 
	{
		$types 				= $this->post_types;
		$types['wp-users'] 	= 'wp-users';
		
		return $types;
	}
	
	/**
	 * Get post types
	 *
	 * @since     1.0
	 */
	private function get_post_types()
	{
		$post_types = get_post_types(array(
			'_builtin' => false
		));
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';
		
		return $post_types;
	}

	/**
	 * Register admin css
	 *
	 * @since     1.0
	 */
	public function admin_styles()
	{
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
			add_option( 'cpac_options', array(&$this, 'get_default_plugin_options') );
		
		register_setting( 'cpac-settings-group', 'cpac_options', array(&$this, 'options_callback') );
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
	private function get_shortened_string($string = '', $charlength = 100) 
	{
		if (!$string)
			return false;
		
		$output = '';
		if ( strlen($string) > $charlength ) {
			$subex 		 = substr($string,0,$charlength-5);
			$exwords 	 = explode(" ",$subex);
			$excut 		 = -(strlen($exwords[count($exwords)-1]));			
			$output 	.= $excut < 0 ? substr($subex,0,$excut) : $subex;			
			$output 	.= "[...]";
		} else {
			$output = $string;
		}
		return $output;
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
				$result = get_the_post_thumbnail($post_id, array(80,80));			
				break;
				
			// Sticky Post
			case "column-sticky" :
				if ( is_sticky($post_id) ) {		
					$src 	= $this->plugin_url('assets/images/checkmark.png');
					$result = "<img alt='sticky' src='{$src}' />";
				}
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
				// all page templates
				$templates 		= get_page_templates();
				// template name
				$result = array_search($page_template, $templates);			
				break;
			
			// Slug
			case "column-page-slug" :
				$result = get_post($post_id)->post_name;			
				break;
			
			// Slug
			case "column-word-count" :
				$result = str_word_count( strip_tags( get_post($post_id)->post_content ) );
				break;
			
			// Taxonomy
			case "column-taxonomy" :
				$tax 	= str_replace('column-taxonomy-', '', $column_name);
				$tags 	= get_the_terms($post_id, $tax);
				$tarr 	= array();
				if ( $tax == 'post_format' && empty($tags) ) {
					$result = __('Standard');
				}
				elseif ( !empty($tags) ) {
					foreach($tags as $tag) {				
						$tarr[] = $tag->name;	
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
			
			default :
				$result = get_post_meta( $post_id, $column_name, true );
						
		endswitch;
		
		if ( empty($result) )
			echo '&nbsp;';
		
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
		
		// Check for user custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-user-meta';
		
		// Hook 
		do_action('cpac-manage-users-column', $type, $column_name, $user_id);
		
		$result = '';
		switch ($type) :			
			
			// user id
			case "column-user_id" :
				$result = $user_id;
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
			
			// user meta data ( custom field )
			case "column-user-meta" :
				$result = $this->get_column_value_custom_field($user_id, $column_name, 'user');
				break;
			
			default :
				$result = get_user_meta( $user_id, $column_name, true );
				
		endswitch;
		
		if ( empty($result) )
			$result = '&nbsp;';
		
		return $result;
	}

	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.0
	 */
	private function get_column_value_attachments( $post_id ) 
	{
		$result = '';
		$attachments = get_posts(array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id
		));
		if ( $attachments ) {
			foreach ( $attachments as $attach ) {
				$result .= wp_get_attachment_image( $attach->ID, array(80,80), true );
			}
		}
		return $result;
	}
	
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.0
	 */	
	private function get_column_value_custom_field($object_id, $column_name, $meta_type = 'post') 
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
				// check if media exists
				$meta = wp_get_attachment_url($meta) ? wp_get_attachment_image( $meta, array(80,80), true ) : '';				
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
				$meta = wp_get_attachment_url($meta) ? wp_get_attachment_image( $meta, array(80,80), true ) : '';				
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
	 * Set columns. These columns apply either for every post or set by a plugin.
	 *
	 * @since     1.0
	 */
	private function filter_preset_columns($columns, $type = 'post') 
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
		$users_columns = $this->get_uniform_format($columns);
		
		return $users_columns;
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
		$custom_columns = array();
		
		// Thumbnail support
		if ( post_type_supports($post_type, 'thumbnail') ) {
			$custom_columns['column-featured_image'] = array(
				'label'			=> __('Featured Image', $this->textdomain),
				'options'		=> array(
					'type_label' 	=> __('Image', $this->textdomain)
				)
			);
		}
		
		// Excerpt support
		if ( post_type_supports($post_type, 'editor') ) {
			$custom_columns['column-excerpt'] = array(
				'label'			=> __('Excerpt', $this->textdomain),
				'options'		=> array(
					'type_label' 	=> __('Excerpt', $this->textdomain)
				)
			);
		}
		
		// Sticky support
		if ( $post_type == 'post' ) {		
			$custom_columns['column-sticky'] = array(
				'label'			=> __('Sticky', $this->textdomain),
				'options'		=> array(
					'type_label' 	=> __('Sticky', $this->textdomain)
				)
			);
		}
		
		// Order support
		if ( post_type_supports($post_type, 'page-attributes') ) {
			$custom_columns['column-order'] = array(
				'label'			=> __('Page Order', $this->textdomain),				
				'options'		=> array(
					'type_label' 	=> __('Order', $this->textdomain),
					'sortorder'		=> 'on',
				)			
			);
		}
		
		// Page Template
		if ( $post_type == 'page' ) { 
			$custom_columns['column-page-template'] = array(
				'label'			=> __('Page Template', $this->textdomain),
				'options'		=> array(
					'type_label' 	=> __('Page Template', $this->textdomain),
					'sortorder'		=> 'on',
				)
			);	
		}
		
		// Post Formats
		if ( post_type_supports($post_type, 'post-formats') ) {
			$custom_columns['column-post_formats'] = array(
				'label'			=> __('Post Format', $this->textdomain),
				'options'		=> array(
					'type_label' 	=> __('Post Format', $this->textdomain)
				)
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
		
		// Post ID support
		$custom_columns['column-postid'] = array(
			'label'			=> 'ID',			
			'options'		=> array(
				'type_label' 	=> 'ID',
				'sortorder'		=> 'on',
			)
		);
		
		// Slug support
		$custom_columns['column-page-slug'] = array(
			'label'			=> __('Slug', $this->textdomain),		
			'options'		=> array(
				'type_label' 	=> __('Slug', $this->textdomain),
				'sortorder'		=> 'on',
			)
		);
		
		// Word count support
		$custom_columns['column-word-count'] = array(
			'label'			=> __('Word count', $this->textdomain),		
			'options'		=> array(
				'type_label' 	=> __('Word count', $this->textdomain),
				'sortorder'		=> 'on'
			)
		);
		
		// Attachment support
		$custom_columns['column-attachment'] = array(
			'label'			=> __('Attachment', $this->textdomain),
			'options'		=> array(
				'type_label' 	=> __('Attachment', $this->textdomain),
			)
		);
		
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
					'class'			=> 'cpac-box-metafield',
					'sortorder'		=> 'on',
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
		$custom_columns = array();
		
		// User ID
		$custom_columns['column-user_id'] = array(
			'label'			=> __('User ID', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('User ID', $this->textdomain),
				'sortorder'		=> 'on'
			)			
		);
		
		// First name
		$custom_columns['column-first_name'] = array(
			'label'			=> __('First name', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('First name', $this->textdomain),
			)			
		);
		
		// Last name
		$custom_columns['column-last_name'] = array(
			'label'			=> __('Last name', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('Last name', $this->textdomain),
			)			
		);
		
		// User url
		$custom_columns['column-user_url'] = array(
			'label'			=> __('Url', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('Url', $this->textdomain),
			)			
		);
		
		// User registration date
		$custom_columns['column-user_registered'] = array(
			'label'			=> __('Registered', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('Registered', $this->textdomain),
			)			
		);
		
		// User description
		$custom_columns['column-user_description'] = array(
			'label'			=> __('Description', $this->textdomain),
			'options'		=> array(
				'type_label'	=> __('Description', $this->textdomain),
			)			
		);
		
		// Custom Field support
		$custom_columns['column-meta-1'] = array(
			'label'			=> __('Custom Field', $this->textdomain),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __('Field', $this->textdomain),
				'class'			=> 'cpac-box-metafield',
				'sortorder'		=> '',
			)			
		);	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-users-columns', $custom_columns);
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
			
			// static values
			'options'		=> array(
				'type_label' 	=> __('Custom', $this->textdomain),
				'hide_options'	=> false,
				'class'			=> 'cpac-box-custom',
				'sortorder'		=> '',
			)
		);
		
		foreach ( $columns as $k => $column ) {
			$c[$k] = wp_parse_args( $column, $defaults);
		}
		
		return $c;
	}

	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	private function get_stored_columns($type)
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
		$referer = '';
		if ( isset($_REQUEST['cpac_type']) && $_REQUEST['cpac_type'] )
			$referer = $_REQUEST['cpac_type'];
			
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

		return "
		<div class='cpac-menu'>
			<ul class='subsubsub'>
				{$menu}
			</ul>
		</div>
		";
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since     1.0
	 */
	private function is_menu_type_current( $post_type ) 
	{	
		// referer
		$referer = '';
		if ( ! empty($_REQUEST['cpac_type']) )
			$referer = $_REQUEST['cpac_type'];
		
		// get label
		$label 		 = $this->get_singular_name($post_type);
		$clean_label = $this->sanitize_string($post_type);
		
		// get first element from post-types
		$first 		= array_shift(array_values($this->post_types));
		
		// display the page that was being viewed before saving
		if ( $referer ) {
			if ( $referer == 'cpac-box-'.$clean_label ) {
				return true;
			}
		
		// settings page has not yet been saved
		} elseif ( $first == $post_type  ) {
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
		// Users
		if ( $type == 'wp-users' )
			$label = 'Users';
			
		// Posts
		else {
			$posttype_obj 	= get_post_type_object($type);
			$label 			= $posttype_obj->labels->singular_name;
		}
		
		return $label;
	}

	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public function handle_requests_orderby_column( $vars ) 
	{
		if ( ! isset( $vars['orderby'] ) )
			return $vars;
			
		$column = $this->get_orderby_type( $vars['orderby'], $vars['post_type'] );
		
		if ( $column ) {
			$id = key($column);
			
			// Page Order
			if ( $id == 'column-order' ) {
				$vars['orderby'] = 'menu_order';
			}
			
			// Custom Fields
			if ( $this->is_column_meta($id) ) {
				$field 		= $column[$id]['field'];
				
				// orderby type
				$field_type = 'meta_value';
				if ( $column[$id]['field_type'] == 'numeric' || $column[$id]['field_type'] == 'library_id' )
					$field_type = 'meta_value_num';
				
				// set vars
				$vars = array_merge( $vars, array(
					'meta_key' 	=> $field,
					'orderby' 	=> $field_type
				) );
			}
			
			// Wordcount
			if ( $id == 'column-word-count' ) {
				// get all posts from this post type
				$all_posts = get_posts(array(
					'numberposts'	=> -1,
					'post_status'	=> 'any', // force all 
					'post_type'		=> $vars['post_type']
				));
				
				// add wordcount to the post ids
				$wordcount_posts = array();
				foreach ( $all_posts as $p ) {
					$wordcount_posts[$p->ID] = str_word_count( strip_tags( $p->post_content ) );
				}
				
				// sort post ids by wordcount
				if ( $vars['order'] == 'asc' )
					asort($wordcount_posts, SORT_NUMERIC);
				else
					arsort($wordcount_posts, SORT_NUMERIC);
					
				// add the sorted post ids to the query with the use of post__in
				$vars['post__in'] = array_keys($wordcount_posts);
				
				// this will make sure WP_Query will use the order of the ids that we have just set in 'post__in'
				add_filter('posts_orderby', array( &$this, 'filter_orderby_post__in'), 10, 2 );
				
				// cleanup the vars we dont need
				unset($vars['order']);
				unset($vars['orderby']);
			}
		}
		
		return $vars;
	}	

	/**
	 * Get orderby type
	 *
	 * @since     1.1
	 */
	private function get_orderby_type($orderby, $type)
	{
		$db_columns = $this->get_stored_columns($type);

		if ( $db_columns ) {
			foreach ( $db_columns as $id => $vars ) {
			
				// check which custom column was clicked
				if ( isset( $vars['label'] ) && $orderby ==  $this->sanitize_string( $vars['label'] ) ) {
					$column[$id] = $vars;
					return $column;
				}
			}
		}
		return false;
	}
	
	/**
	 * Maintain order of ids that are set in the post__in var. 
	 *
	 * This will force the returned posts to use the order of the ID's that 
	 * have been set in post__in. Without this the ID's will be set in numeric order.
	 * See the WP_Query object for more info about the use of post__in.
	 *
	 * @since     1.2.1
	 */
	public function filter_orderby_post__in($orderby, $wp) 
	{	
		// we need the query vars
		$vars = $wp->query_vars;		
		if ( ! empty ( $vars['post__in'] ) ) {			
			// now we can get the ids
			$ids = implode(',', $vars['post__in']);
			
			// by adding FIELD to the SQL query we are forcing the order of the ID's
			return "FIELD (wp_posts.ID,{$ids})";
		}
	}
	
	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since     1.0
	 */
	private function sanitize_string($string) 
	{	
		return str_replace('http://','', esc_url($string) );
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
	private function is_column_meta( $id = '' ) 
	{
		if ( strpos($id, 'column-meta-') !== false )
			return true;
		
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
			$resized = image_resize( $image_path, 120, 80, true);
			
			if ( ! is_wp_error( $resized ) ) {
				$image  = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized);
				
				return "<img src='{$image}' alt='' width='120' height='80' />";
			}
			
			return $resized->get_error_message();
		}
		
		return false;
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
					
			// id
			$id = $this->sanitize_string($type); 
			
			// build draggable boxes
			$boxes = $this->get_column_boxes($type);

			// class
			$class = $this->is_menu_type_current($type) ? ' current' : ' hidden';
			
			$rows .= "
				<tr id='cpac-box-{$id}' valign='top' class='cpac-box-row{$class}'>
					<th class='cpac_post_type' scope='row'>
						{$label}
					</th>
					<td>
						<h3 class='cpac_post_type hidden'>{$label}</h3>
						{$boxes}
					</td>
				</tr>
			";
		}
		
		// Post Type Menu
		$menu = $this->get_menu();
		
	?>
		<div id="cpac" class="wrap">
			<?php screen_icon($this->slug) ?>
			<h2><?php _e('Codepress Admin Columns', $this->textdomain); ?></h2>
			<?php echo $menu ?>
			<div class="postbox-container" style="width:70%;">
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
								
									<?php echo $rows ?>								
									
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
			
			<div class="postbox-container" style="width:20%;">
				<div class="metabox-holder">	
					<div class="meta-box-sortables">						
						
						<div id="likethisplugin-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Like this plugin?', $this->textdomain) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('Why not do any or all of the following', $this->textdomain) ?>:</p>
								<ul>
									<li><a href="http://www.codepress.nl/plugins/codepress-admin-columns/"><?php _e('Link to it so other folks can find out about it.', $this->textdomain) ?></a></li>
									<li><a href="http://wordpress.org/extend/plugins/codepress-admin-columns/"><?php _e('Give it a 5 star rating on WordPress.org.', $this->textdomain) ?></a></li>
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
								<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a> or send me an email %s.', $this->textdomain), 'http://wordpress.org/tags/codepress-admin-columns', '<a href="mailto:info@codepress.nl">info@codepress.nl</a>' );?></p>
								<p><?php printf(__("If you're sure you've found a bug, or have a feature request, please <a href='%s'>submit your feedback</a>.", $this->textdomain), 'http://www.codepress.nl/plugins/codepress-admin-columns#feedback');?></p>
							</div>
						</div><!-- side-cpac-settings -->
					
					</div>
				</div>
			</div><!-- .postbox-container -->
			
		</div>
	<?php
	}
}
?>