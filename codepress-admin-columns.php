<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			2.0
Description: 		Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: 			Codepress
Author URI: 		http://www.admincolumns.com
Plugin URI: 		http://www.admincolumns.com
Text Domain: 		codepress-admin-columns
Domain Path: 		/languages
License:			GPLv2

Copyright 2011-2013  Codepress  info@codepress.nl

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

define( 'CPAC_VERSION', 	 	'2.0' );
define( 'CPAC_TEXTDOMAIN', 	 	'codepress-admin-columns' );
define( 'CPAC_SLUG', 		 	'codepress-admin-columns' );
define( 'CPAC_SETTINGS_SLUG', 	'cpac-settings' );
define( 'CPAC_URL', 			plugins_url( '', __FILE__) );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

/**
 * Dependencies
 *
 * @since 1.3.0
 */
require_once dirname( __FILE__ ) . '/classes/upgrade.php';
require_once dirname( __FILE__ ) . '/classes/utility.php';
require_once dirname( __FILE__ ) . '/classes/columns.php';
require_once dirname( __FILE__ ) . '/classes/columns/posttype.php';
require_once dirname( __FILE__ ) . '/classes/columns/links.php';
require_once dirname( __FILE__ ) . '/classes/columns/users.php';
require_once dirname( __FILE__ ) . '/classes/columns/media.php';
require_once dirname( __FILE__ ) . '/classes/columns/comments.php';

// Sortable columns
require_once dirname( __FILE__ ) . '/classes/sortable.php';
new CPAC_Sortable_Columns();

// Filtering columns
require_once dirname( __FILE__ ) . '/classes/filtering.php';
new CPAC_Filtering_Columns();

// Settings page
include_once dirname( __FILE__ ) . '/classes/settings.php';
new CPAC_Settings;

require_once dirname( __FILE__ ) . '/classes/export_import.php';
require_once dirname( __FILE__ ) . '/classes/license.php';
require_once dirname( __FILE__ ) . '/classes/third_party.php';
require_once dirname( __FILE__ ) . '/classes/deprecated.php';

/**
 * The Codepress Admin Columns Class
 *
 * @since 1.0.0
 *
 */
class Codepress_Admin_Columns
{
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'wp_loaded', array( $this, 'init') );

		// upgrade
		register_activation_hook( __FILE__, array( 'CPAC_Upgrade', 'upgrade' ) );
	}

	/**
	 * Initialize plugin.
	 *
	 * Loading sequence is determined and intialized.
	 *
	 * @since 1.0.0
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

		// add settings link
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);
	}

	/**
	 * Add Settings link to plugin page
	 *
	 * @since 1.0.0
	 *
	 * @param string $links All settings links.
	 * @param string $file Plugin filename.
	 * @return string Link to settings page
	 */
	function add_settings_link( $links, $file )
	{
		if ( $file != plugin_basename( __FILE__ ) )
			return $links;

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=' . CPAC_SLUG . '">' . __( 'Settings' ) . '</a>');
		return $links;
	}

	/**
	 * Register Column Values
	 *
	 * Initializes each Class per type
	 *
	 * @since 1.0.0
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
	 * Register Columns Headings
	 *
	 * Register column headings for posttypes, users, media, links and comments
	 *
	 * @since 1.0.0
	 */
	public function register_columns_headings()
	{
		/** Posts */
	 	foreach ( CPAC_Utility::get_post_types() as $post_type ) {
			add_filter("manage_edit-{$post_type}_columns",  array( $this, 'add_columns_headings_posts' ) );
		}

		/** Users */
		// give higher priority, so it will load just before other plugins to prevent conflicts
		add_filter( "manage_users_columns",  array( $this, 'add_columns_headings_users' ), 9 );

		/** Media */
		add_filter( "manage_upload_columns",  array( $this, 'add_columns_headings_media' ) );

		/** Links */
		add_filter( "manage_link-manager_columns",  array( $this, 'add_columns_headings_links' ) );

		/** Comments */
		add_filter( "manage_edit-comments_columns", array( $this, 'add_columns_headings_comments' ) );
	}

	/**
	 * Callback add Posts Column
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Registered columns
	 * @return array Register columns with CPAC columns
	 */
	public function add_columns_headings_posts( $columns )
	{
		if( ! $post_type = get_post_type() )
			return $columns;

		$type = new CPAC_Columns_Posttype( $post_type );

		return $type->add_columns_headings( $columns );
	}

	/**
	 * Callback add Users column
	 *
	 * @since 1.1.0
	 *
	 * @param array $columns Registered columns
	 * @return array Register columns with CPAC columns
	 */
	public function add_columns_headings_users( $columns )
	{
		$type = new CPAC_Columns_Users;

		return $type->add_columns_headings( $columns );
	}

	/**
	 * Callback add Media column
	 *
	 * @since 1.3.0
	 *
	 * @param array $columns Registered columns
	 * @return array Register columns with CPAC columns
	 */
	public function add_columns_headings_media( $columns )
	{
		$type = new CPAC_Columns_Media;

		return $type->add_columns_headings( $columns );
	}

	/**
	 * Callback add Links column
	 *
	 * @since 1.3.1
	 *
	 * @param array $columns Registered columns
	 * @return array Register columns with CPAC columns
	 */
	public function add_columns_headings_links( $columns )
	{
		$type = new CPAC_Columns_Links;

		return $type->add_columns_headings( $columns );
	}

	/**
	 * Callback add Comments column
	 *
	 * @since  1.3.1
	 *
	 * @param array $columns Registered columns
	 * @return array Register columns with CPAC columns
	 */
	public function add_columns_headings_comments($columns)
	{
		$type = new CPAC_Columns_Comments;

		return $type->add_columns_headings( $columns );
	}

	/**
	 * Register column css
	 *
	 * @since 1.0.0
	 */
	public function column_styles()
	{
		wp_enqueue_style( 'cpac-columns', CPAC_URL.'/assets/css/column.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * Admin body class
	 *
	 * Adds a body class which is used to set individual column widths
	 *
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 * @return string
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
		foreach ( CPAC_Utility::get_types() as $type ) {

			// match against screen or wp-screen
			if ( $type->storage_key == $screen || $type->storage_key == "wp-{$screen}" )
				$classes .= " cp-{$type->storage_key}";
		}

		return $classes;
	}


	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	function admin_css()
	{
		$css_column_width = '';

		// loop throug the available types...
		foreach ( CPAC_Utility::get_types() as $type ) {

			if ( ! $cols = CPAC_Utility::get_stored_columns( $type->storage_key ) )
				continue;

			// loop through each available column...
			foreach ( $cols as $col_name => $col ) {

				// and check for stored width and add it to the css
				if (!empty($col['width']) && is_numeric($col['width']) && $col['width'] > 0 ) {
					$css_column_width .= ".cp-{$type->storage_key} .wrap table th.column-{$col_name} { width: {$col['width']}% !important; }";
				}
			}
		}

		echo
		"<style type='text/css'>
			{$css_column_width}
			#adminmenu #toplevel_page_codepress-admin-columns .wp-menu-image {
				background: transparent url(" . CPAC_URL . "/assets/images/icon_20.png) no-repeat 6px -24px;
			}
			#adminmenu #toplevel_page_codepress-admin-columns:hover .wp-menu-image,
			#adminmenu #toplevel_page_codepress-admin-columns.wp-menu-open .wp-menu-image {
				background-position: 6px 6px;
			}
		</style>";
	}
}

/**
 * Init Class Codepress_Admin_Columns
 *
 * @since 1.0.0
 */
new Codepress_Admin_Columns();