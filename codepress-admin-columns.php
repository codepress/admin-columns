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

Copyright 2011-2017  AdminColumns.com  info@admincolumns.com

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
 */
class CPAC {

	/**
	 * Basename of the plugin, retrieved through plugin_basename function
	 *
	 * @since 1.0
	 * @var string
	 */
	private $plugin_basename;

	/**
	 * Admin Columns settings class instance
	 *
	 * @since  2.2
	 * @access private
	 * @var AC_Admin
	 */
	private $admin;

	/**
	 * @var AC_Groups Column Groups
	 */
	private $column_groups;

	/**
	 * @var AC_Groups Listscreen Groups
	 */
	private $list_screen_groups;

	/**
	 * @var AC_TableScreen
	 */
	private $table_screen;

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
	 * @var array $notices
	 */
	private $notices;

	/**
	 * @since 2.5
	 */
	private static $_instance = null;

	/**
	 * @since 2.5
	 */
	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @since 1.0
	 */
	private function __construct() {
		$this->plugin_basename = plugin_basename( __FILE__ );

		// Backwards compatibility
		define( 'CPAC_VERSION', $this->get_version() );
		define( 'CPAC_URL', $this->get_plugin_url() );
		define( 'CPAC_DIR', $this->get_plugin_dir() );

		// Autoload classes
		$this->autoloader()->register_prefix( 'AC_', $this->get_plugin_dir() . 'classes/' );

		require_once $this->get_plugin_dir() . 'api.php';
		require_once $this->get_plugin_dir() . 'classes/Column.php';

		// Third Party
		new AC_ThirdParty_ACF();
		new AC_ThirdParty_NinjaForms();
		new AC_ThirdParty_WooCommerce();
		new AC_ThirdParty_WPML();

		// Init
		$this->admin = new AC_Admin();
		$this->table_screen = new AC_TableScreen();
		$this->helper = new AC_Helper();

		new AC_Notice_Review();

		// Hooks
		add_action( 'init', array( $this, 'localize' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );

		// Notices
		add_action( 'admin_notices', array( $this, 'display_notices' ) );
		add_action( 'network_admin_notices', array( $this, 'display_notices' ) );

		add_action( 'plugins_loaded', array( $this, 'ready' ) );

		// Set capabilities
		register_activation_hook( __FILE__, array( $this, 'set_capabilities' ) );

		add_action( 'admin_init', array( $this, 'set_capabilities_multisite' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function ready() {
		do_action( 'ac/ready', $this );
	}

	/**
	 * @return AC_Autoloader
	 */
	public function autoloader() {
		require_once $this->get_plugin_dir() . 'classes/autoloader.php';

		return AC_Autoloader::instance();
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
	 * @since 2.2
	 * @uses  load_plugin_textdomain()
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( $this->plugin_basename ) . '/languages/' );
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
	 * @return bool True when user can manage admin columns
	 */
	public function user_can_manage_admin_columns() {
		return current_user_can( 'manage_admin_columns' );
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
	 * @see   filter:plugin_action_links
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file === $this->plugin_basename ) {
			array_unshift( $links, ac_helper()->html->link( AC()->admin()->get_link( 'settings' ), __( 'Settings' ) ) );
		}

		return $links;
	}

	/**
	 * @since 2.5
	 */
	public function use_delete_confirmation() {
		return apply_filters( 'ac/delete_confirmation', true );
	}

	/**
	 * @since 2.2
	 * @return AC_Admin Settings class instance
	 */
	public function admin() {
		return $this->admin;
	}

	/**
	 * Get admin columns add-ons class instance
	 *
	 * @since 2.2
	 * @return AC_Admin_Page_Addons Add-ons class instance
	 */
	public function addons() {
		return $this->admin()->get_page( 'addons' );
	}

	/**
	 * Column groups
	 */
	public function set_column_groups() {
		$groups = new AC_Groups();

		$groups->register_group( 'default', __( 'Default', 'codepress-admin-columns' ), 5 );
		$groups->register_group( 'custom_field', __( 'Custom Fields', 'codepress-admin-columns' ), 6 );
		$groups->register_group( 'plugin', __( 'Plugins', 'codepress-admin-columns' ), 7 );
		$groups->register_group( 'custom', __( 'Custom', 'codepress-admin-columns' ), 40 );

		foreach ( $this->addons()->get_missing_addons() as $addon ) {
			$groups->register_group( $addon->get_slug(), $addon->get_title(), 5 );
		}

		$this->column_groups = $groups;

		do_action( 'ac/column_groups', $groups );
	}

	/**
	 * @return AC_Groups
	 */
	public function column_groups() {
		if ( null === $this->column_groups ) {
			$this->set_column_groups();
		}

		return $this->column_groups;
	}

	/**
	 * Contains simple helper methods
	 *
	 * @since NEWVERSION
	 *
	 * @return AC_Helper
	 */
	public function helper() {
		return $this->helper;
	}

	/**
	 * @return AC_TableScreen Returns the screen manager for the list table
	 */
	public function table_screen() {
		return $this->table_screen;
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

		return $screens[ $key ];
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function list_screen_exists( $key ) {
		return $this->get_list_screen( $key ) ? true : false;
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

		// Post types
		foreach ( $this->get_post_types() as $post_type ) {
			$this->register_list_screen( new AC_ListScreen_Post( $post_type ) );
		}

		$this->register_list_screen( new AC_ListScreen_Media() );
		$this->register_list_screen( new AC_ListScreen_Comment() );

		// Users, not for network users
		if ( ! is_multisite() ) {
			$this->register_list_screen( new AC_ListScreen_User() );
		}

		// as of 3.5 link manager is removed
		if ( get_option( 'link_manager_enabled' ) ) {
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
	 * Column groups
	 */
	public function set_list_screen_groups() {
		$groups = new AC_Groups();

		$groups->register_group( 'post', __( 'Post Type' ), 5 );
		$groups->register_group( 'user', __( 'Users' ) );
		$groups->register_group( 'media', __( 'Media' ) );
		$groups->register_group( 'comment', __( 'Comments' ) );
		$groups->register_group( 'link', __( 'Links' ), 15 );

		$this->list_screen_groups = $groups;

		do_action( 'ac/list_screen_groups', $groups );
	}

	/**
	 * @return AC_Groups
	 */
	public function list_screen_groups() {
		if ( null === $this->list_screen_groups ) {
			$this->set_list_screen_groups();
		}

		return $this->list_screen_groups;
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
		return apply_filters( 'ac/post_types', $post_types );
	}

	/**
	 * Display admin notice
	 */
	public function display_notices() {
		if ( $this->notices ) {
			echo implode( $this->notices );
		}
	}

	/**
	 * @param string $message Message body
	 * @param string $type    'updated', 'error' or 'notice-warning'
	 */
	public function notice( $message, $type = 'updated' ) {
		$this->notices[] = '<div class="cpac_message notice ' . esc_attr( $type ) . '"><p>' . $message . '</p></div>';
	}

	/**
	 * @return AC_Admin_Page_Columns
	 */
	public function admin_columns_screen() {
		return $this->admin()->get_page( 'columns' );
	}

	/**
	 * @since NEWVERSION
	 */
	public function admin_scripts() {
		wp_register_script( 'ac-sitewide-notices', AC()->get_plugin_url() . "assets/js/cpac-message" . AC()->minified() . ".js", array( 'jquery' ), AC()->get_version() );
		wp_register_style( 'ac-sitewide-notices', AC()->get_plugin_url() . "assets/css/cpac-message" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );

	}
}

/**
 * @deprecated NEWVERSION
 */
function cpac() {
	return AC();
}

/**
 * @since NEWVERSION
 */
function AC() {
	return CPAC::instance();
}

// Global for backwards compatibility.
$GLOBALS['cpac'] = AC();