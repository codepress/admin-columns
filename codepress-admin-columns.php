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
require_once dirname( __FILE__ ) . '/classes/utility.php';
require_once dirname( __FILE__ ) . '/classes/columns.php';
require_once dirname( __FILE__ ) . '/classes/columns/posttype.php';
require_once dirname( __FILE__ ) . '/classes/columns/links.php';
require_once dirname( __FILE__ ) . '/classes/columns/users.php';
require_once dirname( __FILE__ ) . '/classes/columns/media.php';
require_once dirname( __FILE__ ) . '/classes/columns/comments.php';

// Sortable columns
require_once dirname( __FILE__ ) . '/classes/sortable.php';
new Codepress_Sortable_Columns();

// Settings page
include_once dirname( __FILE__ ) . '/classes/settings.php';
new Cpac_Settings;

require_once dirname( __FILE__ ) . '/classes/export_import.php';
require_once dirname( __FILE__ ) . '/classes/license.php';
require_once dirname( __FILE__ ) . '/classes/third_party.php';

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
	 	foreach ( cpac_utility::get_post_types() as $post_type ) {
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
	 *	Callback add Posts Column
	 *
	 * 	@since     1.0
	 */
	public function add_columns_headings_posts( $columns )
	{
		$type = new cpac_columns_posttype( get_post_type() );

		return $type->add_columns_headings( $columns );
	}

	/**
	 *	Callback add Users column
	 *
	 * 	@since     1.1
	 */
	public function add_columns_headings_users( $columns )
	{
		$type = new cpac_columns_users;

		return $type->add_columns_headings( $columns );
	}

	/**
	 *	Callback add Media column
	 *
	 * 	@since     1.3
	 */
	public function add_columns_headings_media( $columns )
	{
		$type = new cpac_columns_media;

		return $type->add_columns_headings( $columns );
	}

	/**
	 *	Callback add Links column
	 *
	 * 	@since     1.3.1
	 */
	public function add_columns_headings_links( $columns )
	{
		$type = new cpac_columns_links;

		return $type->add_columns_headings( $columns );
	}

	/**
	 *	Callback add Comments column
	 *
	 * 	@since     1.3.1
	 */
	public function add_columns_headings_comments($columns)
	{
		$type = new cpac_columns_comments;

		return $type->add_columns_headings( $columns );
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
		foreach ( cpac_utility::get_types() as $type ) {

			// match against screen or wp-screen
			if ( $type->type == $screen || $type->type == "wp-{$screen}" )
				$classes .= " cp-{$type->type}";
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
		foreach ( cpac_utility::get_types() as $type ) {

			if ( ! $cols = cpac_utility::get_stored_columns($type->type) )
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