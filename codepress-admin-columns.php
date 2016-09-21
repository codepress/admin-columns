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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	 * @var AC_ListingsScreen $listings_screen
	 */
	private $_listings_screen;

	/**
	 * Registered storage model class instances
	 * Array of AC_StorageModel instances, with the storage model keys (e.g. post, page, wp-users) as keys
	 *
	 * @since 2.0
	 * @var AC_StorageModel[]
	 */
	private $storage_models;

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

		$classes_dir = $this->get_plugin_dir() . 'classes/';

		require_once $classes_dir . 'autoloader.php';

		$autoloader = AC_Autoloader::instance();
		$autoloader->register_prefix( 'AC_', $classes_dir );

		require_once $this->get_plugin_dir() . 'api.php';

		// Third Party
		new AC_ThirdParty_ACF();
		new AC_ThirdParty_NinjaForms();
		new AC_ThirdParty_WooCommerce();
		new AC_ThirdParty_WPML();

		// Includes
		$this->_settings = new AC_Admin();
		$this->_addons = new AC_Addons();
		$this->_upgrade = new AC_Upgrade();
		$this->_listings_screen = new AC_ListingsScreen();

		$this->helper = new AC_Helper();

		new AC_Notice_Review();

		// Hooks
		add_action( 'init', array( $this, 'localize' ) );
		add_action( 'wp_loaded', array( $this, 'after_setup' ) ); // Setup callback, important to load after set_storage_models
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );

		// Set capabilities
		register_activation_hook( __FILE__, array( $this, 'set_capabilities' ) );

		add_action( 'admin_init', array( $this, 'set_capabilities_multisite' ) );
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
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $this->version ) {
			$this->version = $this->get_plugin_version( __FILE__ );
		}

		return $this->version;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_plugin_version( $file ) {
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
	 * Get registered storage models
	 *
	 * @since 2.5
	 * @return AC_StorageModel[]
	 */
	public function get_storage_models() {
		if ( empty( $this->storage_models ) ) {

			$storage_models = array();

			$classes_dir = $this->get_plugin_dir() . 'classes/';

			require_once $classes_dir . 'Column.php';

			// Backwards compatibility
			require_once $classes_dir . 'Deprecated/column-default.php';

			// @deprecated NEWVERSION
			require_once $classes_dir . 'Deprecated/storage_model.php';

			// Create a storage model per post type
			foreach ( $this->get_post_types() as $post_type ) {

				$storage_model = new AC_StorageModel_Post();
				$storage_model->set_post_type( $post_type );

				$storage_models[ $storage_model->key ] = $storage_model;
			}

			// Create other storage models
			$storage_model = new AC_StorageModel_User();
			$storage_models[ $storage_model->key ] = $storage_model;

			$storage_model = new AC_StorageModel_Media();
			$storage_models[ $storage_model->key ] = $storage_model;

			$storage_model = new AC_StorageModel_Comment();
			$storage_models[ $storage_model->key ] = $storage_model;

			if ( apply_filters( 'pre_option_link_manager_enabled', false ) ) { // as of 3.5 link manager is removed
				$storage_model = new AC_StorageModel_Link();
				$storage_models[ $storage_model->key ] = $storage_model;
			}

			/**
			 * Filter the available storage models
			 * Used by external plugins to add additional storage models
			 *
			 * @since 2.0
			 *
			 * @param array $storage_models List of storage model class instances ( [key] => [AC_StorageModel object], where [key] is the storage key, such as "user", "post" or "my_custom_post_type")
			 * @param object $this CPAC
			 */
			$this->storage_models = apply_filters( 'cac/storage_models', $storage_models, $this );
		}

		return $this->storage_models;
	}

	/**
	 * Retrieve a storage model object based on its key
	 *
	 * @since 2.0
	 *
	 * @param string $key Storage model key (e.g. post, page, wp-users)
	 *
	 * @return bool|AC_StorageModel Storage Model object (or false, on failure)
	 */
	public function get_storage_model( $key ) {
		$models = $this->get_storage_models();

		return isset( $models[ $key ] ) ? $models[ $key ] : false;
	}

	/**
	 * Get column object
	 *
	 * @since 2.5.4
	 *
	 * @param $storage_key AC_StorageModel->key
	 * @param $layout_id AC_StorageModel->layout
	 * @param $column_name CPAC_Column->name
	 *
	 * @return false|CPAC_Column
	 */
	public function get_column( $storage_key, $layout_id, $column_name ) {
		if ( ! $storage_model = $this->get_storage_model( $storage_key ) ) {
			return false;
		}
		$storage_model->set_active_layout( $layout_id );

		return $storage_model->get_column_by_name( $column_name );
	}

	/**
	 * Get storage model object of currently active storage model
	 * On the users overview page, for example, this returns the AC_StorageModel object
	 *
	 * @since 2.2.4
	 *
	 * @return AC_StorageModel
	 */
	public function get_current_storage_model() {
		return $this->_listings_screen->get_storage_model();
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
	 * @return AC_ListingsScreen
	 */
	public function listings_screen() {
		return $this->_listings_screen;
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