<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			2.0.0
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

define( 'CPAC_VERSION', 	 	'2.0.0' );
define( 'CPAC_TEXTDOMAIN', 	 	'codepress-admin-columns' );
define( 'CPAC_SLUG', 		 	'codepress-admin-columns' );
define( 'CPAC_SETTINGS_SLUG', 	'cpac-settings' );
define( 'CPAC_URL', 			plugins_url( '', __FILE__ ) );
define( 'CPAC_DIR', 			plugin_dir_path( __FILE__ ) );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

// DEV
require_once dirname( __FILE__ ) . '/addons/multiple-fields/multiple-fields.php';
require_once dirname( __FILE__ ) . '/addons/sortable/sortable.php';
	
/**
 * Dependencies
 *
 * @since 1.3.0
 */
require_once dirname( __FILE__ ) . '/classes/upgrade.php';
require_once dirname( __FILE__ ) . '/classes/utility.php';

// columns
require_once dirname( __FILE__ ) . '/classes/column.php';

// storage models
require_once dirname( __FILE__ ) . '/classes/storage_model.php';
require_once dirname( __FILE__ ) . '/classes/storage_model/post.php';

// includes
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
class CPAC
{
	public $storage_models;
	
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'wp_loaded', array( $this, 'init') );
		
		// upgrade
		//register_activation_hook( __FILE__, array( 'CPAC_Upgrade', 'upgrade' ) );
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
		//add_action( 'admin_init', array( $this, 'register_columns_headings' ) );
		//add_action( 'admin_init', array( $this, 'register_columns_values' ) );		
		
		// add settings link
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);
		
		// init
		$this->set_storage_models();
		
		$this->init_controllers();
		
		// Hook
		do_action( 'cpac_loaded', $this );
	}
	
	/**
	 * Get storage models
	 *
	 * @since 2.0.0
	 *
	 */
	private function set_storage_models() {
	
		$storage_models = array();
		
		// include parent and childs
		require_once dirname( __FILE__ ) . '/classes/storage_model.php';
		require_once dirname( __FILE__ ) . '/classes/storage_model/post.php';
		
		// add models
		foreach ( CPAC_Utility::get_post_types() as $post_type ) {
			$storage_model = new CPAC_Storage_Model_Post( $post_type );
			
			$storage_models[ $storage_model->key ] = $storage_model;
		}
		
		// @todo: hook to add more models		
		
		$this->storage_models = $storage_models;
	}
	
	/**
	 * Get storage model
	 *
	 * @since 2.0.0
	 *
	 * @return array object Storage Model
	 */
	public function get_storage_model( $key ) {

		if ( ! isset( $this->storage_models[ $key ] ) )
			return false;
		
		return $this->storage_models[ $key ];
	}
	
	/**
	 * Init controllers
	 *
	 * @since 2.0.0
	 *
	 */
	 function init_controllers() {
		
		// Settings
		include_once CPAC_DIR . '/classes/settings.php';
		new CPAC_Settings( $this );
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
	 * Register column css
	 *
	 * @since 1.0.0
	 */
	public function column_styles()
	{
		global $pagenow;
		
		if ( in_array( $pagenow, array( 'edit.php', 'upload.php', 'link-manager.php', 'edit-comments.php', 'users.php' ) ) ) {
			wp_enqueue_style( 'cpac-columns', CPAC_URL.'/assets/css/column.css', array(), CPAC_VERSION, 'all' );
		}
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
		/* foreach ( CPAC_Utility::get_storage_models() as $type ) {

			// match against screen or wp-screen
			if ( $type->key == $screen || $type->key == "wp-{$screen}" )
				$classes .= " cp-{$type->key}";
		} */

		return $classes;
	}


	/**
	 * Admin CSS for Column width and Settings Icon
	 * 
	 * @todo: let column object determine width
	 * @since 1.4.0
	 */
	function admin_css()
	{
		$css_column_width = '';

		// loop throug the available types...
		/* foreach ( CPAC_Utility::get_storage_models() as $type ) {

			if ( ! $cols = CPAC_Utility::get_stored_columns( $type->key ) )
				continue;

			// loop through each available column...
			foreach ( $cols as $col_name => $col ) {

				// and check for stored width and add it to the css
				if (!empty($col['width']) && is_numeric($col['width']) && $col['width'] > 0 ) {
					$css_column_width .= ".cp-{$type->key} .wrap table th.column-{$col_name} { width: {$col['width']}% !important; }";
				}
			}
		} */

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
$cpac = new CPAC();