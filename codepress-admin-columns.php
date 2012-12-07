<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			1.4.6.5
Description: 		Customise columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: 			Codepress
Author URI: 		http://www.codepress.nl
Plugin URI: 		http://www.codepress.nl/plugins/codepress-admin-columns/
Text Domain: 		codepress-admin-columns
Domain Path: 		/languages
License:			GPLv2

Copyright 2011-2012  Codepress  info@codepress.nl

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

define( 'CPAC_VERSION', 	'1.4.6.5' );
define( 'CPAC_TEXTDOMAIN', 	'codepress-admin-columns' );
define( 'CPAC_SLUG', 		'codepress-admin-columns' );
define( 'CPAC_URL', 		plugins_url('', __FILE__) );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

/**
 * Dependencies
 *
 * @since     1.3
 */
require_once dirname( __FILE__ ) . '/static.php';
require_once dirname( __FILE__ ) . '/classes/columns.php';
require_once dirname( __FILE__ ) . '/classes/third_party.php';
require_once dirname( __FILE__ ) . '/classes/sortable.php';
require_once dirname( __FILE__ ) . '/classes/export_import.php';
require_once dirname( __FILE__ ) . '/classes/license.php';

/**
 * Codepress Admin Columns Class
 *
 * @since     1.0
 *
 */
class Codepress_Admin_Columns 
{	
	private $post_types,
			$codepress_url,
			$wordpress_url,
			$api_url,
			$admin_page;
	
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
		$this->post_types 		= cpac_static::get_post_types();
				
		// set
		$this->codepress_url	= 'http://www.codepress.nl/plugins/codepress-admin-columns';
		$this->plugins_url		= 'http://wordpress.org/extend/plugins/codepress-admin-columns/';			
		$this->wordpress_url	= 'http://wordpress.org/tags/codepress-admin-columns';				
				
		// translations
		load_plugin_textdomain( CPAC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu') );		
		add_action( 'admin_init', array( $this, 'register_settings') );

		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'column_styles') );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_head', array( $this, 'admin_css') );
		
		// register columns
		add_action( 'admin_init', array( $this, 'register_columns_headings' ) );
		add_action( 'admin_init', array( $this, 'register_columns_values' ) );	
		
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
			esc_html__( 'Admin Columns Settings', CPAC_TEXTDOMAIN ), 
			// Menu Title
			esc_html__( 'Admin Columns', CPAC_TEXTDOMAIN ), 
			// Capability
			'manage_options',
			// Menu slug
			CPAC_SLUG,
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
	 * @since	1.0
	 * @param	$links string - all settings links
	 * @param	$file string - plugin filename
	 * @return	string - link to settings page
	 */
	function add_settings_link( $links, $file ) 
	{
		if ( $file != plugin_basename( __FILE__ ))
			return $links;

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=' . CPAC_SLUG . '">' . __( 'Settings' ) . '</a>');
		return $links;
	}	
		
	/**
	 *	Register Column Values	
	 *
	 *	initializes each Class per type
	 *
	 * 	@since     1.0
	 */
	public function register_columns_values()
	{
		require_once dirname( __FILE__ ) . '/classes/values.php';		
		require_once dirname( __FILE__ ) . '/classes/values/posts.php';
		require_once dirname( __FILE__ ) . '/classes/values/users.php';
		require_once dirname( __FILE__ ) . '/classes/values/media.php';
		require_once dirname( __FILE__ ) . '/classes/values/link.php';
		require_once dirname( __FILE__ ) . '/classes/values/comments.php';
		
		// Init
		new CPAC_Posts_Values();
		new CPAC_Link_Values();
		new CPAC_Media_Values();
		new CPAC_Users_Values();
		new CPAC_Comments_Values();
	}
	/**
	 *	Register Columns Headings
	 *
	 *	apply_filters location in includes/screen.php
	 *
	 * 	@since     1.0
	 */
	public function register_columns_headings()
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
		return $this->add_columns_headings( get_post_type(), $columns);		
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
	protected function add_columns_headings( $type, $columns ) 
	{		
		// only get stored columns.. the rest we don't need
		$db_columns	= cpac_static::get_stored_columns($type);

		if ( !$db_columns )
			return $columns;
		
		// filter already loaded columns by plugins
		$set_columns = cpac_static::filter_preset_columns( $type, $columns );

		// loop through columns
		foreach ( $db_columns as $id => $values ) {			
			// is active
			if ( isset($values['state']) && $values['state'] == 'on' ){				
				
				$label = $values['label'];
				
				// exception for comments
				if( 'comments' == $id ) {
					$label = cpac_static::get_comment_icon();
				}
				
				// register format
				$set_columns[$id] = $label;
			}
		}
		
		return $set_columns;
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
		if ( $state ) {
			$classes[] = 'active';
		}
		if ( ! empty($values['options']['class']) ) {
			$classes[] = $values['options']['class'];
		}
		$class = implode(' ', $classes);
			
		// more box options	
		$more_options 	= $this->get_additional_box_options($type, $id, $values);
		$action 		= "<a class='cpac-action' href='#open'>open</a>";
				
		// type label
		$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';
		
		// label
		$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';
		
		// main label
		$main_label = $values['label'];	
		
		// main label exception for comments
		if ( 'comments' == $id ) {
			$main_label = cpac_static::get_comment_icon();
		}
		
		// width
		$width			= isset($values['width']) ? $values['width'] : 0;
		$width_descr	= isset($values['width']) && $values['width'] > 0 ? $values['width'] . '%' : __('default', CPAC_TEXTDOMAIN);
		
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
					<label class='main-label'>{$main_label}</label>
				</div>
				<div class='cpac-meta-title'>
					{$action}
					<span>{$type_label}</span>
				</div>
				<div class='cpac-type-inside'>				
					<label for='cpac_options-{$type}-{$id}-label'{$label_hidden}>Label: </label>
					<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' id='cpac_options-{$type}-{$id}-label' value='{$label}' class='text'{$label_hidden}/>
					<label for='cpac_options-{$type}-{$id}-width'>" . __('Width', CPAC_TEXTDOMAIN) . ":</label>			
					<input type='hidden' maxlength='4' class='input-width' name='cpac_options[columns][{$type}][{$id}][width]' id='cpac_options-{$type}-{$id}-width' value='{$width}' />
					<div class='description width-decription' title='" . __('default', CPAC_TEXTDOMAIN) . "'>{$width_descr}</div>
					<div class='input-width-range'></div>
					<br/>
					{$more_options}
				</div>
			</li>
		";
		
		return $list;
	}
	

	/**
	 * Register admin scripts
	 *
	 * @since     1.0
	 */
	public function admin_scripts() 
	{
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui-slider' );		
		wp_enqueue_script( 'cpac-qtip2', CPAC_URL.'/assets/js/jquery.qtip.js', array('jquery'), CPAC_VERSION );
		wp_enqueue_script( 'cpac-admin', CPAC_URL.'/assets/js/admin-column.js', array('jquery', 'dashboard', 'jquery-ui-sortable'), CPAC_VERSION );
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
	 * Register admin css
	 *
	 * @since     1.0
	 */
	public function admin_styles()
	{
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'jquery-ui-lightness', CPAC_URL.'/assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), CPAC_VERSION, 'all' );	
		wp_enqueue_style( 'cpac-admin', CPAC_URL.'/assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );	
	}
	
	/**
	 * Register column css
	 *
	 * @since     1.0
	 */
	public function column_styles()
	{
		wp_enqueue_style( 'cpac-columns', CPAC_URL.'/assets/css/column.css', array(), CPAC_VERSION, 'all' );	
	}

	/**
	 * Register plugin options
	 *
	 * @since     1.0
	 */
	public function register_settings() 
	{
		// If we have no options in the database, let's add them now.
		if ( false === get_option('cpac_options') ) {
			add_option( 'cpac_options', $this->get_default_plugin_options() );
		}
		
		register_setting( 'cpac-settings-group', 'cpac_options', array($this, 'options_callback') );
	}	

	/**
	 * Returns the default plugin options.
	 *
	 * @since     1.0
	 */
	public function get_default_plugin_options() 
	{
		return apply_filters( 'cpac_default_plugin_options', array() );
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
		// only handle updates from the admin columns page
		if ( isset($_REQUEST['page']) && CPAC_SLUG == $_REQUEST['page'] ) {
				
			// settings updated
			if ( ! empty($_REQUEST['settings-updated']) ) {
				$this->store_wp_default_columns();
			}
			
			// restore defaults 
			if ( ! empty($_REQUEST['cpac-restore-defaults']) ) {
				$this->restore_defaults();
			}
		}
	}
	
	/**
	 * Stores WP default columns
	 *
	 * This will store columns that are set by WordPress core or theme
	 *
	 * @since     1.2
	 */
	private function store_wp_default_columns() 
	{	
		// stores the default columns that are set by WP or theme.
		$wp_default_columns = array();
				
		// Posts
		foreach ( $this->post_types as $post_type ) {
			$cpac_columns = new cpac_columns( $post_type );
			$wp_default_columns[$post_type] = $cpac_columns->get_wp_default_posts_columns();
		}
				
		$cpac_columns = new cpac_columns();
		
		// Users
		$wp_default_columns['wp-users'] = $cpac_columns->get_wp_default_users_columns();
		
		// Media
		$wp_default_columns['wp-media'] = $cpac_columns->get_wp_default_media_columns();
		
		// Links
		$wp_default_columns['wp-links'] = $cpac_columns->get_wp_default_links_columns();
		
		// Comments
		$wp_default_columns['wp-comments'] = $cpac_columns->get_wp_default_comments_columns();		
		
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
			$label 		 = cpac_static::get_singular_name($type);
			$clean_label = cpac_static::sanitize_string($type);
			
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
		$options_btn = "<a href='#cpac-box-plugin_settings' class='cpac-settings-link{$class_current_settings}'>".__('Settings / Addons', CPAC_TEXTDOMAIN)."</a>";
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
		$clean_label = cpac_static::sanitize_string($type);
		
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
	 * Admin body class
	 *
	 * @since     1.4
	 */
	function admin_class( $classes ) 
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
				$classes .= " cp-{$type}";
		}

		return $classes;
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
			$cols = cpac_static::get_stored_columns($type);	
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
	 * Ajax activation
	 *
	 * @since     1.3.1
	 */
	public function ajax_activation()
	{
		// keys
		$key 	= $_POST['key'];
		$type 	= $_POST['type'];
		
		$licence = new cpac_licence( $type );
		
		// update key
		if ( $key == 'remove' ) {
			$licence->remove_license_key();
		}
			
		// set license key
		elseif ( $licence->check_remote_key( $key ) ) {
		
			// set key
			$licence->set_license_key( $key );
			
			// returned masked key
			echo json_encode( $licence->get_masked_license_key() );
		}

		exit;
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
	 * Plugin Settings
	 *
	 * @since     1.3.1
	 */
	private function plugin_settings() 
	{
		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current' : ' hidden'; '';
		
		/** Sortable */
		$masked_key 				= '';
		$class_sortorder_activate 	= '';
		$class_sortorder_deactivate = ' hidden';
		
		// is unlocked
		$licence = new cpac_licence('sortable');
		
		if ( $licence->is_unlocked() ) {
			$masked_key 	 = $licence->get_masked_license_key('sortable');
			$class_sortorder_activate = ' hidden';
			$class_sortorder_deactivate = '';
		}
		
		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='button-primary alignright' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";
		
		// info box
		$sortable_tooltip = "
			<p>".__('This will make all of the new columns support sorting', CPAC_TEXTDOMAIN).".</p>
			<p>".__('By default WordPress let\'s you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong>', CPAC_TEXTDOMAIN)."</p>
			<p>".__('Perfect for sorting your articles, media files, comments, links and users', CPAC_TEXTDOMAIN).".</p>
			<p class='description'>".__('(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN).".</p>			
			<img src='" . CPAC_URL.'/assets/images/addon_sortable_1.png' . "' alt='' />
			{$find_out_more}
		";
		
		// addons 
		$addons = "
			<tr>
				<td colspan='2'>
					<h2>".__('Activate Add-ons', CPAC_TEXTDOMAIN)."</h2>
					<p>".__('Add-ons can be unlocked by purchasing a license key. Each key can be used on multiple sites', CPAC_TEXTDOMAIN)." <a target='_blank' href='{$this->codepress_url}/sortorder-addon/'>Visit the Plugin Store</a>.</p>
					<table class='widefat addons'>
						<thead>
							<tr>
								<th class='activation_type'>".__('Addon', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_status'>".__('Status', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_code'>".__('Activation Code', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_more'></th>
							</tr>
						</thead>
						<tbody>
							<tr id='cpac-activation-sortable' class='last'>
								<td class='activation_type'>
									<span>" . __('Sortorder', CPAC_TEXTDOMAIN) . "</span>
									<div class='cpac-tooltip hidden'>
										<div class='qtip_title'>" . __('Sortorder', CPAC_TEXTDOMAIN) . "</div>
										<div class='qtip_content'>
											<p>" . __($sortable_tooltip, CPAC_TEXTDOMAIN) . "</p>
										</div>
									</div>
								</td>
								<td class='activation_status'>
									<div class='activate{$class_sortorder_activate}'>
										" . __('Inactive', CPAC_TEXTDOMAIN) . "
									</div>
									<div class='deactivate{$class_sortorder_deactivate}'>
										" . __('Active', CPAC_TEXTDOMAIN) . "
									</div>
								</td>
								<td class='activation_code'>
									<div class='activate{$class_sortorder_activate}'>
										<input type='text' value='" . __('Fill in your activation code', CPAC_TEXTDOMAIN) . "' name='cpac-sortable-key'>
										<a href='javascript:;' class='button'>" . __('Activate', CPAC_TEXTDOMAIN) . "<span></span></a>
									</div>
									<div class='deactivate{$class_sortorder_deactivate}'>
										<span class='masked_key'>{$masked_key}</span>
										<a href='javascript:;' class='button'>" . __('Deactivate', CPAC_TEXTDOMAIN) . "<span></span></a>
									</div>
									<div class='activation-error-msg'></div>
								</td>
								<td class='activation_more'>{$find_out_more}</td>
							</tr><!-- #cpac-activation-sortable -->
						</tbody>					
					</table>
					<div class='addon-translation-string hidden'>
						<span class='tstring-fill-in'>" . __('Enter your activation code', CPAC_TEXTDOMAIN) . "</span>
						<span class='tstring-unrecognised'>" . __('Activation code unrecognised', CPAC_TEXTDOMAIN) . "</span>
					</div>
				</td>
			</tr>
		";
		
		// import / export
		$export_selections = array();		
		foreach ( $this->get_types() as $type ) {			
			$export_selections[] = "<option value='{$type}'>" . cpac_static::get_singular_name($type) . "</option>";
		}
		
		$export_import = "
			<tr>
				<td class='first-col'>
					<h2>" . __('Export Settings.', CPAC_TEXTDOMAIN ) . "</h2>
					<p>" . __('You this export to migrate your admin column settings from one WordPress site to another.', CPAC_TEXTDOMAIN ) . ":</p>
					<p><a href='#' class='cpac-pointer' rel='cpac-import-instructions-html'>" . __('Instructions', CPAC_TEXTDOMAIN ) . "</a></p>
					<div id='cpac-import-instructions-html' style='display:none;'>
						<h3>" . __('Import Columns Types', CPAC_TEXTDOMAIN ) . "</h3>
						<p>" . __('Instructions', CPAC_TEXTDOMAIN ) . ":</p>						
						<ol>
							<li>" . __('Select one or more types.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Click Export.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Copy the generated code to your clipboard.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Go to you other site and paste it under Import Settings.', CPAC_TEXTDOMAIN ) . "</li>					
						</ol>						
					</div>
				</td>
				<td>
					<div class='cpac_export'>
						<select size='" . count($export_selections) . "' multiple='multiple' class='select' id='cpac_export_types'>
							" . implode( $export_selections ) . "
						</select>
						<br/>						
						<a id='cpac_export_submit' class='button' href='javascript:;'>" . __('Export', CPAC_TEXTDOMAIN ) . "<span></span></a>
						<div class='export-message'></div>
					</div>
					<div id='cpac_export_output'>						
						<textarea rows='" . count($export_selections) . "'></textarea>
						<p class='description'>" . __('Copy the following code to your clipboard. Then you can use this code for importing.', CPAC_TEXTDOMAIN ) . "</p>
					</div>
				</td>
			</tr>
			<tr class='last'>
				<td class='first-col'>
					<h2>" . __('Import settings', CPAC_TEXTDOMAIN ) . "</h2>
					<p>" . __('Copy and paste your import settings here.', CPAC_TEXTDOMAIN ) . "</p>
				</td>
				<td>
					<div id='cpac_import_input'>
						<textarea rows='10'></textarea>
						<a id='cpac_import_submit' class='button' href='javascript:;'>" . __('Import', CPAC_TEXTDOMAIN ) . "<span></span></a>
						<div class='import-message'></div>
					</div>
				</td>
			</tr>
		";
		
		// general options
		$general_options = "
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
		";
		
		// settings
		$row = "
		<tr id='cpac-box-plugin_settings' valign='top' class='cpac-box-row {$class_current_settings}'>
			<td colspan='2'>
				<table class='nopadding'>
					{$addons}
					{$export_import}
					{$general_options}
				</table>
			</td>
		</tr><!-- #cpac-box-plugin_settings -->
		";
		
		return $row;
	}	
	
	
	/**
	 * Settings Page Template.
	 *
	 * This function in conjunction with others uses the WordPress
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
			$label = cpac_static::get_singular_name($type);
			
			// id
			$id = cpac_static::sanitize_string($type); 
			
			// build draggable boxes
			$cpac_columns = new cpac_columns( $type );
			
			$boxes = $cpac_columns->get_column_boxes();

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
		
		// General Setttings
		$general_settings = $this->plugin_settings();
		
		// Post Type Menu
		$menu = $this->get_menu();
		
		// Help screen message
		$help_text = '';
		if ( version_compare( get_bloginfo('version'), '3.2', '>' ) )
			$help_text = '<p>'.__('You will find a short overview at the <strong>Help</strong> section in the top-right screen.', CPAC_TEXTDOMAIN).'</p>';
		
		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='alignright green' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";
		
	?>
		<div id="cpac" class="wrap">
			<?php screen_icon(CPAC_SLUG) ?>
			<h2><?php _e('Codepress Admin Columns', CPAC_TEXTDOMAIN); ?></h2>
			<?php echo $menu ?>
			
			<div class="postbox-container cpac-col-right">
				<div class="metabox-holder">	
					<div class="meta-box-sortables">						
						
						<div id="addons-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Get the Addon', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('By default WordPress let\'s you only sort by title, date, comments and author.', CPAC_TEXTDOMAIN) ?></p>
								<p><?php _e('Make <strong>all columns</strong> of <strong>all types</strong> within the plugin support sorting &#8212; with the sorting addon.', CPAC_TEXTDOMAIN) ?></p>
								<p class="description"><?php _e('(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN) ?>.</p>
								<?php echo $find_out_more ?>
							</div>
						</div><!-- addons-cpac-settings -->
												
						<div id="likethisplugin-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Like this plugin?', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('Why not do any or all of the following', CPAC_TEXTDOMAIN) ?>:</p>
								<ul>
									<li><a href="<?php echo $this->plugins_url ?>"><?php _e('Give it a 5 star rating on WordPress.org.', CPAC_TEXTDOMAIN) ?></a></li>
									<li><a href="<?php echo $this->codepress_url ?>/"><?php _e('Link to it so other folks can find out about it.', CPAC_TEXTDOMAIN) ?></a></li>									
									<li class="donate_link"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZDZRSYLQ4Z76J"><?php _e('Donate a token of your appreciation.', CPAC_TEXTDOMAIN) ?></a></li>
								</ul>								
							</div>
						</div><!-- likethisplugin-cpac-settings -->
						
						<div id="latest-news-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Follow us', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">								
								<ul>
									<li class="twitter"><a href="http://twitter.com/codepressNL"><?php _e('Follow Codepress on Twitter.', CPAC_TEXTDOMAIN) ?></a></li>
									<li class="facebook"><a href="https://www.facebook.com/codepressNL"><?php _e('Like Codepress on Facebook.', CPAC_TEXTDOMAIN) ?></a></li>
						
								</ul>								
							</div>
						</div><!-- latest-news-cpac-settings -->
						
						<div id="side-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Need support?', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">								
								<?php echo $help_text ?>
								<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a> or send me an email %s.', CPAC_TEXTDOMAIN), $this->wordpress_url, '<a href="mailto:info@codepress.nl">info@codepress.nl</a>' );?></p>
								<p><?php printf(__("If you're sure you've found a bug, or have a feature request, please <a href='%s'>submit your feedback</a>.", CPAC_TEXTDOMAIN), "{$this->codepress_url}/feedback");?></p>
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
								<span><?php _e('Admin Columns', CPAC_TEXTDOMAIN ); ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="options.php">
								
								<?php settings_fields( 'cpac-settings-group' ); ?>
								
								<table class="form-table">
									<!-- columns -->
									<?php echo $rows; ?>								
									
									<!-- activation -->
									<?php echo $general_settings; ?>
									
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
								<span><?php _e('Restore defaults', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="">					
									<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e('Restore default settings', CPAC_TEXTDOMAIN ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", CPAC_TEXTDOMAIN); ?>');" />
								</form>
								<p class="description"><?php _e('This will delete all column settings and restore the default settings.', CPAC_TEXTDOMAIN); ?></p>
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