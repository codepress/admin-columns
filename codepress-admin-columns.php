<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			1.5
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

define( 'CPAC_VERSION', 	'1.5' );
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
require_once dirname( __FILE__ ) . '/classes/columns/posttype.php';
require_once dirname( __FILE__ ) . '/classes/columns/links.php';
require_once dirname( __FILE__ ) . '/classes/columns/users.php';
require_once dirname( __FILE__ ) . '/classes/columns/media.php';
require_once dirname( __FILE__ ) . '/classes/columns/comments.php';
require_once dirname( __FILE__ ) . '/classes/third_party.php';

// Sortable columns
//require_once dirname( __FILE__ ) . '/classes/sortable.php';
//new Codepress_Sortable_Columns();

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
	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{	
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
		// translations
		load_plugin_textdomain( CPAC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'column_styles') );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_head', array( $this, 'admin_css') );
		
		// register columns
		add_action( 'admin_init', array( $this, 'register_columns_headings' ) );
		add_action( 'admin_init', array( $this, 'register_columns_values' ) );	
		
		// filters
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);
		
		// Settings page
		include_once dirname( __FILE__ ) . '/classes/settings.php';
		new cpac_settings;		
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
	 	foreach ( cpac_static::get_post_types() as $post_type ) {

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
	 * Register column css
	 *
	 * @since     1.0
	 */
	public function column_styles()
	{
		wp_enqueue_style( 'cpac-columns', CPAC_URL.'/assets/css/column.css', array(), CPAC_VERSION, 'all' );	
	}	
	
	/**
	 * Admin body class
	 *
	 * @description: Adds a body class which is used to set individual column widths
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
		foreach ( cpac_static::get_types() as $type ) {			
			
			// match against screen or wp-screen
			if ( $type->type == $screen || $type->type == "wp-{$screen}" )
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
		foreach ( cpac_static::get_types() as $type ) {
			
			if ( ! $cols = cpac_static::get_stored_columns($type->type) )
				continue;
			
			// loop through each available column...
			foreach ( $cols as $col_name => $col ) {
			
				// and check for stored width and add it to the css
				if (!empty($col['width']) && is_numeric($col['width']) && $col['width'] > 0 ) {
					$css .= ".cp-{$type} .wrap table th.column-{$col_name} { width: {$col['width']}% !important; }";
				}
			}
		}
		
		echo "<style type='text/css'>{$css}</style>";
	}	
}

/**
 * Init Class Codepress_Admin_Columns
 *
 * @since     1.0
 */
new Codepress_Admin_Columns();


?>