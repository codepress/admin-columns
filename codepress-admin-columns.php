<?php
/*
Plugin Name: Admin Columns
Version: 2.6beta
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPLv2

Copyright 2011-2016  AdminColumns.com  info@admincolumns.com

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

// Only run plugin in the admin interface
if ( ! is_admin() ) {
	return false;
}

/**
 * The Admin Columns Class
 *
 * @since 1.0
 *
 * @property AC_Helper helper
 */
class CPAC {

	/**
	 * Admin Columns add-ons class instance
	 *
	 * @since 2.2
	 * @access private
	 * @var AC_Addons
	 */
	private $_addons;

	/**
	 * Admin Columns settings class instance
	 *
	 * @since 2.2
	 * @access private
	 * @var AC_Admin
	 */
	private $_settings;

	/**
	 * Admin Columns plugin upgrade class instance
	 *
	 * @since 2.2.7
	 * @access private
	 * @var AC_Upgrade
	 */
	private $_upgrade;

	/**
	 * @var AC_ListScreenManager $_list_screen_manager
	 */
	private $_list_screen_manager;

	/**
	 * @since NEWVERSION
	 * @var null|string $version Version number
	 */
	private $version = null;

	/**
	 * @since NEWVERSION
	 * @var AC_Helper
	 */
	private $helper;

	/**
	 * @since NEWVERSION
	 * @var AC_ListScreen[]
	 */
	private $list_screens;

	/**
	 * @since 2.5
	 */
	protected static $_instance = null;

	/**
	 * @since 2.5
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @since 1.0
	 */
	function __construct() {

		// Backwards compatibility
		define( 'CPAC_VERSION', $this->get_version() );
		define( 'CPAC_URL', $this->get_plugin_url() );
		define( 'CPAC_DIR', $this->get_plugin_dir() );

		$this->autoloader()->register_prefix( 'AC_', $this->get_plugin_dir() . 'classes/' );

		require_once $this->get_plugin_dir() . 'api.php';
		require_once $this->get_plugin_dir() . 'classes/Column.php';

		// Backwards compatibility
		// todo: AC_Column_Default is no longer called . Still required?
		//require_once $this->get_plugin_dir() . 'classes/Deprecated/column-default.php';

		// Third Party
		new AC_ThirdParty_ACF();
		new AC_ThirdParty_NinjaForms();
		new AC_ThirdParty_WooCommerce();
		new AC_ThirdParty_WPML();

		// Includes
		$this->_settings = new AC_Admin();
		$this->_addons = new AC_Addons();
		$this->_upgrade = new AC_Upgrade();
		$this->_list_screen_manager = new AC_ListScreenManager();

		$this->helper = new AC_Helper();

		new AC_Notice_Review();

		// Hooks
		add_action( 'init', array( $this, 'localize' ) );
		add_action( 'wp_loaded', array( $this, 'after_setup' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );

		// Set capabilities
		register_activation_hook( __FILE__, array( $this, 'set_capabilities' ) );

		add_action( 'admin_init', array( $this, 'set_capabilities_multisite' ) );
	}

	/**
	 * @return AC_Autoloader
	 */
	public function autoloader() {
		require_once $this->get_plugin_dir() . 'classes/autoloader.php';

		return AC_Autoloader::instance();
	}

	/**
	 * Auto-load in-accessible properties on demand
	 *
	 * @since NEWVERSION
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'helper' ) ) ) {
			return $this->$key();
		}

		return false;
	}

	/**
	 * @since NEWVERSION
	 * @return string Path to plugin dir
	 */
	public function get_plugin_dir() {
		return plugin_dir_path( __FILE__ );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_plugin_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_version() {
		if ( null === $this->version ) {
			$this->version = $this->get_plugin_version( __FILE__ );
		}

		return $this->version;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_plugin_version( $file ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$plugin = get_plugin_data( $file, false, false );

		return isset( $plugin['Version'] ) ? $plugin['Version'] : false;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_upgrade_version() {
		return '2.0.0';
	}

	/**
	 * Fire callbacks for admin columns setup completion
	 *
	 * @since 2.2
	 */
	public function after_setup() {

		/**
		 * Fires when Admin Columns is fully loaded
		 * Use this for setting up addon functionality
		 *
		 * @since 2.0
		 *
		 * @param CPAC $cpac_instance Main Admin Columns plugin class instance
		 */
		do_action( 'cac/loaded', $this );
	}

	/**
	 * @since 2.2
	 * @uses load_plugin_textdomain()
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * @since NEWVERSION
	 */
	public function minified() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * Add caps for Multisite admins
	 */
	public function set_capabilities_multisite() {
		if ( is_multisite() && current_user_can( 'administrator' ) ) {
			$this->set_capabilities();
		}
	}

	/**
	 * Add capability to administrator to manage admin columns.
	 * You can use the capability 'manage_admin_columns' to grant other roles this privilege as well.
	 *
	 * @since 2.0.4
	 */
	public function set_capabilities() {
		if ( $role = get_role( 'administrator' ) ) {
			$role->add_cap( 'manage_admin_columns' );
		}
	}

	/**
	 * Disable side wide notices
	 *
	 * @return bool True when no notices should be displayed
	 */
	public function suppress_site_wide_notices() {
		return apply_filters( 'ac/suppress_site_wide_notices', false );
	}

	/**
	 * Add a settings link to the Admin Columns entry in the plugin overview screen
	 *
	 * @since 1.0
	 * @see filter:plugin_action_links
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file != plugin_basename( __FILE__ ) ) {
			return $links;
		}

		array_unshift( $links, '<a href="' . esc_url( admin_url( "options-general.php?page=codepress-admin-columns" ) ) . '">' . __( 'Settings' ) . '</a>' );

		return $links;
	}

	/**
	 * @since 2.5
	 */
	public function use_delete_confirmation() {
		return apply_filters( 'ac/delete_confirmation', true );
	}

	/**
	 * Get admin columns settings class instance
	 *
	 * @since 2.2
	 * @return AC_Admin Settings class instance
	 */
	public function settings() {
		return $this->_settings;
	}

	/**
	 * Get admin columns add-ons class instance
	 *
	 * @since 2.2
	 * @return AC_Addons Add-ons class instance
	 */
	public function addons() {
		return $this->_addons;
	}

	/**
	 * Get admin columns upgrade class instance
	 *
	 * @since 2.2.7
	 * @return AC_Upgrade Upgrade class instance
	 */
	public function upgrade() {
		return $this->_upgrade;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return AC_Helper
	 */
	public function helper() {
		return $this->helper;
	}

	/**
	 * @return AC_ListScreenManager
	 */
	public function list_screen_manager() {
		return $this->_list_screen_manager;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $key
	 *
	 * @return AC_ListScreen|false
	 */
	public function get_list_screen( $key ) {
		$screens = $this->get_list_screens();

		if ( ! isset( $screens[ $key ] ) ) {
			return false;
		}

		$screen = $screens[ $key ];

		return $screen;
	}

	/**
	 * Returns the default list screen when no choice is made by the user
	 *
	 * @since NEWVERSION
	 * @return AC_ListScreen
	 */
	public function get_default_list_screen() {
		$screens = $this->get_list_screens();
		$default_screen = array_shift( $screens );

		return $default_screen;
	}

	/**
	 * Get registered list screens
	 *
	 * @since NEWVERSION
	 * @return AC_ListScreen[]
	 */
	public function get_list_screens() {
		if ( null === $this->list_screens ) {
			$this->set_list_screens();
		}

		return $this->list_screens;
	}

	/**
	 * Get registered list screens
	 *
	 * @since NEWVERSION
	 */
	private function set_list_screens() {
		// Create a list screen per post type
		foreach ( $this->get_post_types() as $post_type ) {
			$this->register_list_screen( new AC_ListScreen_Post( $post_type ) );
		}

		// Create other list screens
		$this->register_list_screen( new AC_ListScreen_User() );
		$this->register_list_screen( new AC_ListScreen_Media() );
		$this->register_list_screen( new AC_ListScreen_Comment() );

		if ( apply_filters( 'pre_option_link_manager_enabled', false ) ) { // as of 3.5 link manager is removed
			$this->register_list_screen( new AC_ListScreen_Link() );
		}

		// @since NEWVERSION
		do_action( 'ac/list_screens', $this );
	}

	/**
	 * @param AC_ListScreen $list_screen
	 */
	public function register_list_screen( AC_ListScreen $list_screen ) {
		$this->list_screens[ $list_screen->get_key() ] = $list_screen;
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since 1.0
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	public function get_post_types() {
		$post_types = array();

		if ( post_type_exists( 'post' ) ) {
			$post_types['post'] = 'post';
		}
		if ( post_type_exists( 'page' ) ) {
			$post_types['page'] = 'page';
		}

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) ) );

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'cac/post_types', $post_types );
	}

	/**
	 * @return AC_Admin_Tab_Columns
	 */
	public function columns_tab() {
		return $this->settings()->get_tab( 'columns' );
	}

	/**
	 * Get list screen object of currently active list screen
	 * On the users overview page, for example, this returns the AC_ListScreen object
	 *
	 * @since 2.2.4
	 * @deprecated NEWVERSION
	 *
	 * @return false
	 */
	public function get_storage_model( $key ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC()->get_list_screen()' );

		return $this->get_list_screen( $key );
	}

	/**
	 * Get list screen object of currently active list screen
	 * On the users overview page, for example, this returns the AC_ListScreen object
	 *
	 * @since 2.2.4
	 * @deprecated NEWVERSION
	 *
	 * @return AC_ListScreen
	 */
	public function get_current_storage_model() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC()->get_current_list_screen()' );

		return $this->list_screen_manager()->get_list_screen();
	}

	/**
	 * @since 2.1.1
	 */
	public function get_general_option( $option ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC()->settings()->get_general_option( $option )' );

		return $this->settings()->get_general_option( $option );
	}

	/**
	 * Whether the current screen is the Admin Columns settings screen
	 *
	 * @since 2.2
	 *
	 * @param string $tab Specifies a tab screen (optional)
	 *
	 * @return bool True if the current screen is the settings screen, false otherwise
	 */
	public function is_settings_screen( $tab = '' ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC()->settings()->is_current_tab( $tab )' );

		return $this->settings()->is_current_tab( $tab );
	}

	/**
	 * Get a list of taxonomies supported by Admin Columns
	 *
	 * @since 1.0
	 *
	 * @return array List of taxonomies
	 */
	public function get_taxonomies() {
		_deprecated_function( __METHOD__, 'NEWVERSION' );

		return array();
	}

}

// @deprecated since NEWVERSION
function cpac() {
	return AC();
}

// @since NEWVERSION
function AC() {
	return CPAC::instance();
}

// Global for backwards compatibility.
$GLOBALS['cpac'] = AC();