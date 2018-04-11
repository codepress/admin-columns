<?php
/*
Plugin Name: Admin Columns
Version: 3.1.7
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPL v3

Admin Columns Plugin
Copyright (C) 20011-2017, Admin Columns - info@admincolumns.com
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return false;
}

require_once 'classes/Autoloader.php';
require_once 'classes/Addon.php';
require_once 'classes/Plugin.php';
require_once 'api.php';

/**
 * The Admin Columns Class
 *
 * @since 1.0
 */
class CPAC extends AC_Plugin {

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
	 * @since 3.0
	 * @var AC_Helper
	 */
	private $helper;

	/**
	 * @since 3.0
	 * @var AC_ListScreen[]
	 */
	private $list_screens;

	/**
	 * @var AC_API
	 */
	private $api;

	/**
	 * @var AC_Admin_Addons
	 */
	private $addons;

	/**
	 * @since 2.5
	 */
	private static $instance = null;

	/**
	 * @since 2.5
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @since 1.0
	 */
	private function __construct() {
		// Backwards compatibility
		define( 'CPAC_VERSION', $this->get_version() );
		define( 'CPAC_URL', $this->get_plugin_url() );
		define( 'CPAC_DIR', $this->get_plugin_dir() );

		$this->autoloader()->register_prefix( $this->get_prefix(), $this->get_plugin_dir() . 'classes/' );

		new AC_Screen();

		// Third Party
		new AC_ThirdParty_ACF();
		new AC_ThirdParty_NinjaForms();
		new AC_ThirdParty_WooCommerce();
		new AC_ThirdParty_WPML();

		// Init
		$this->addons = new AC_Admin_Addons();
		$this->admin = new AC_Admin();
		$this->table_screen = new AC_TableScreen();
		$this->helper = new AC_Helper();
		$this->api = new AC_API();

		add_action( 'init', array( $this, 'init_capabilities' ) );
		add_action( 'init', array( $this, 'localize' ) );
		add_action( 'init', array( $this, 'install' ) );
		add_action( 'init', array( $this, 'checks' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
		add_action( 'after_setup_theme', array( $this, 'ready' ) );
	}

	/**
	 * Init checks
	 */
	// TODO
	public function checks() {
		$checks = array(
			new AC_Check_Review(),
			new AC_Check_AddonAvailable(),
		);

		foreach ( $checks as $check ) {
			$check->register();
		}
	}

	/**
	 * @return string
	 */
	protected function get_file() {
		return __FILE__;
	}

	/**
	 * @return string
	 */
	protected function get_version_key() {
		return 'ac_version';
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return '3.1.7';
	}

	public function get_prefix() {
		return 'AC_';
	}

	public function ready() {
		/**
		 * For loading external resources, e.g. column settings.
		 * Can be called from plugins and themes.
		 */
		do_action( 'ac/ready', $this );
	}

	/**
	 * @return AC_Autoloader
	 */
	public function autoloader() {
		return AC_Autoloader::instance();
	}

	/**
	 * @since 2.2
	 * @uses  load_plugin_textdomain()
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( $this->get_basename() ) . '/languages/' );
	}

	/**
	 * Initialize current user and make sure any administrator user can use Admin Columns
	 *
	 * @since NEWVERSION
	 */
	public function init_capabilities() {
		$caps = new AC_Capabilities();

		if ( ! $caps->is_administrator() ) {
			return;
		}

		register_activation_hook( $this->get_file(), array( $caps, 'add_manage' ) );

		if ( $caps->has_manage() ) {
			return;
		}

		add_action( 'admin_init', array( $caps, 'add_manage' ) );
	}

	/**
	 * Add a settings link to the Admin Columns entry in the plugin overview screen
	 *
	 * @since 1.0
	 * @see   filter:plugin_action_links
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file === $this->get_basename() ) {
			array_unshift( $links, ac_helper()->html->link( AC()->admin()->get_link( 'columns' ), __( 'Settings', 'codepress-admin-columns' ) ) );
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
	 * @since 3.0
	 * @return AC_API
	 */
	public function api() {
		return $this->api;
	}

	/**
	 * @since 2.2
	 * @return AC_Admin Settings class instance
	 */
	public function admin() {
		return $this->admin;
	}

	/**
	 * @since 2.2
	 * @return AC_Admin_Addons Add-ons class instance
	 */
	public function addons() {
		return $this->addons;
	}

	/**
	 * Column groups
	 */
	public function set_column_groups() {
		$groups = new AC_Groups();
		$groups->register_group( 'default', __( 'Default', 'codepress-admin-columns' ) );
		$groups->register_group( 'plugin', __( 'Plugins' ), 20 );
		$groups->register_group( 'custom_field', __( 'Custom Fields', 'codepress-admin-columns' ), 30 );
		$groups->register_group( 'custom', __( 'Custom', 'codepress-admin-columns' ), 40 );

		foreach ( $this->addons()->get_missing_addons() as $addon ) {
			$groups->register_group( $addon->get_slug(), $addon->get_title(), 11 );
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
	 * @since 3.0
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
	 * @since 3.0
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
	 * Get registered list screens
	 *
	 * @since 3.0
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
	 * @since 3.0
	 */
	private function set_list_screens() {

		$list_screens = array();

		// Post types
		foreach ( $this->get_post_types() as $post_type ) {
			$list_screens[] = new AC_ListScreen_Post( $post_type );
		}

		$list_screens[] = new AC_ListScreen_Media();
		$list_screens[] = new AC_ListScreen_Comment();

		// Users, not for network users
		if ( ! is_multisite() ) {
			$list_screens[] = new AC_ListScreen_User();
		}

		foreach ( $list_screens as $list_screen ) {
			$this->register_list_screen( $list_screen );
		}

		/**
		 * Register list screens
		 *
		 * @since 3.0
		 *
		 * @param CPAC $this
		 */
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

		$groups->register_group( 'post', __( 'Post Type', 'codepress-admin-columns' ), 5 );
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
	 * @return AC_Admin_Page_Columns
	 */
	public function admin_columns_screen() {
		return $this->admin()->get_page( 'columns' );
	}

	/**
	 * @return bool True when doing ajax
	 */
	public function is_doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * @deprecated 3.1.5
	 *
	 * @param WP_Screen $wp_screen
	 */
	public function get_list_screen_by_wpscreen( $wp_screen ) {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

	/**
	 * @deprecated 3.1.5
	 * @since      3.0
	 */
	public function get_plugin_version( $file ) {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

	/**
	 * Returns the default list screen when no choice is made by the user
	 *
	 * @deprecated 3.1.5
	 * @since      3.0
	 */
	public function get_default_list_screen() {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

	/**
	 * @deprecated 3.1.5
	 * @since      3.0
	 */
	public function minified() {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

}

/**
 * @deprecated 3.0
 */
function cpac() {
	return AC();
}

/**
 * @since 3.0
 * @return CPAC
 */
function AC() {
	return CPAC::instance();
}

// Global for backwards compatibility.
$GLOBALS['cpac'] = AC();