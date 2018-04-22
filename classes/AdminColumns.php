<?php

namespace AC;

use AC\ThirdParty;
use AC\Check;

/**
 * The Admin Columns Class
 *
 * @since 1.0
 */
class AdminColumns extends Plugin {

	/**
	 * Admin Columns settings class instance
	 *
	 * @since  2.2
	 * @access private
	 * @var Admin
	 */
	private $admin;

	/**
	 * @var TableScreen
	 */
	private $table_screen;

	/**
	 * @var API
	 */
	private $api;

	/**
	 * @var Admin\Addons
	 */
	private $addons;

	/**
	 * @var ListScreen[]
	 */
	private $list_screens;

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
		// TODO: check this
		new Screen();

		// Third Party
		new ThirdParty\ACF();
		new ThirdParty\NinjaForms();
		new ThirdParty\WooCommerce();
		new ThirdParty\WPML();

		// Init
		$this->addons = new Admin\Addons();

		$this->admin = new Admin();
		$this->admin->register();

		$this->table_screen = new TableScreen();
		$this->api = new API();

		add_action( 'init', array( $this, 'init_capabilities' ) );
		add_action( 'init', array( $this, 'install' ) );
		add_action( 'init', array( $this, 'notice_checks' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
		add_action( 'after_setup_theme', array( $this, 'ready' ) );
	}

	/**
	 * Init checks
	 */
	public function notice_checks() {
		$checks = array(
			new Check\Review(),
			new Check\AddonAvailable(),
		);

		foreach ( $checks as $check ) {
			$check->register();
		}
	}

	/**
	 * @return string
	 */
	protected function get_file() {
		return AC_FILE;
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
		return 'AC';
	}

	public function ready() {
		/**
		 * For loading external resources, e.g. column settings.
		 * Can be called from plugins and themes.
		 */
		do_action( 'ac/ready', $this );
	}

	/**
	 * Initialize current user and make sure any administrator user can use Admin Columns
	 *
	 * @since NEWVERSION
	 */
	public function init_capabilities() {
		$caps = new Capabilities();

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
	 * @return API
	 */
	public function api() {
		return $this->api;
	}

	/**
	 * @since 2.2
	 * @return Admin Settings class instance
	 */
	public function admin() {
		return $this->admin;
	}

	/**
	 * @since 2.2
	 * @return Admin\Addons Add-ons class instance
	 */
	public function addons() {
		return $this->addons;
	}

	/**
	 * @return TableScreen Returns the screen manager for the list table
	 */
	public function table_screen() {
		return $this->table_screen;
	}

	/**
	 * @return Admin\Page\Columns
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
	 * @return ListScreen[]
	 */
	public function get_list_screens() {
		if ( null === $this->list_screens ) {
			$this->register_list_screens();
		}

		return $this->list_screens;
	}

	/**
	 * @param ListScreen $list_screen
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		$this->list_screens[ $list_screen->get_key() ] = $list_screen;
	}

	/**
	 * Register List Screens
	 */
	public function register_list_screens() {
		$list_screens = array();

		// Post types
		foreach ( $this->get_post_types() as $post_type ) {
			$list_screens[] = new ListScreen\Post( $post_type );
		}

		$list_screens[] = new ListScreen\Media();
		$list_screens[] = new ListScreen\Comment();

		// Users, not for network users
		if ( ! is_multisite() ) {
			$list_screens[] = new ListScreen\User();
		}

		foreach ( $list_screens as $list_screen ) {
			$this->register_list_screen( $list_screen );
		}

		do_action( 'ac/list_screens', $this );
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since 1.0
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	public function get_post_types() {
		$post_types = get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) );

		foreach ( array( 'post', 'page' ) as $builtin ) {
			if ( post_type_exists( $builtin ) ) {
				$post_types[ $builtin ] = $builtin;
			}
		}

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

	/**
	 * @since      3.0
	 * @deprecated NEWVERSION
	 *
	 * @param string $key
	 *
	 * @return ListScreen|false
	 */
	public function get_list_screen( $key ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ListScreenFactory::create()' );

		return ListScreenFactory::create( $key );
	}

	/**
	 * @param string $key
	 *
	 * @deprecated NEWVERSION
	 *
	 * @return bool
	 */
	public function list_screen_exists( $key ) {
		_deprecated_function( __METHOD__, 'NEWVERSION' );

		return ListScreenFactory::create( $key ) ? true : false;
	}

	/**
	 * @deprecated NEWVERSION
	 *
	 * @return Groups
	 */
	public function list_screen_groups() {
		_deprecated_function( __METHOD__, '3.1.5', 'ListScreenGroups::get_groups' );

		return ListScreenGroups::get_groups();
	}

	/**
	 *
	 * @deprecated NEWVERSION
	 * @return Groups
	 */
	public function column_groups() {
		_deprecated_function( __METHOD__, 'NEWVERSION' );

		return new Groups();
	}

	/**
	 * Contains simple helper methods
	 *
	 * @since      3.0
	 * @deprecated NEWVERSION
	 *
	 * @return Helper
	 */
	public function helper() {
		_deprecated_function( __METHOD__, '3.1.5', 'ac_helper()' );

		return ac_helper();
	}

}