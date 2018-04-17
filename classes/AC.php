<?php

use AC\Capabilities;

/**
 * The Admin Columns Class
 *
 * @since 1.0
 */
class AC extends \AC_Plugin {

	/**
	 * Admin Columns settings class instance
	 *
	 * @since  2.2
	 * @access private
	 * @var AC_Admin
	 */
	private $admin;

	/**
	 * @var AC_TableScreen
	 */
	private $table_screen;

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

		// TODO: check this
		new \AC_Screen();

		// Third Party
		new \AC_ThirdParty_ACF();
		new \AC_ThirdParty_NinjaForms();
		new \AC_ThirdParty_WooCommerce();
		new \AC_ThirdParty_WPML();

		// Init
		$this->addons = new AC_Admin_Addons();

		$this->admin = new AC_Admin();
		$this->admin->register();

		$this->table_screen = new AC_TableScreen();
		$this->api = new AC_API();

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
			new \AC_Check_Review(),
			new \AC_Check_AddonAvailable(),
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
	 * @return AC_TableScreen Returns the screen manager for the list table
	 */
	public function table_screen() {
		return $this->table_screen;
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
	 * @param AC_ListScreen $list_screen
	 */
	public function register_list_screen( AC_ListScreen $list_screen ) {
		AC_ListScreenFactory::register_list_screen( $list_screen );
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

	/**
	 * @since      3.0
	 * @deprecated NEWVERSION
	 *
	 * @param string $key
	 *
	 * @return AC_ListScreen|false
	 */
	public function get_list_screen( $key ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC_ListScreenFactory::create()' );

		return AC_ListScreenFactory::create( $key );
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

		return AC_ListScreenFactory::create( $key ) ? true : false;
	}

	/**
	 * Get registered list screens
	 *
	 * @since      3.0
	 * @deprecated NEWVERSION
	 *
	 * @return AC_ListScreen[]
	 */
	public function get_list_screens() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC_ListScreenFactory::get_types()' );

		return AC_ListScreenFactory::get_types();
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since      1.0
	 * @deprecated NEWVERSION
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	public function get_post_types() {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'AC_ListScreenFactory::get_post_types()' );

		return AC_ListScreenFactory::get_post_types();
	}

	/**
	 * @deprecated NEWVERSION
	 *
	 * @return AC_Groups
	 */
	public function list_screen_groups() {
		_deprecated_function( __METHOD__, '3.1.5', 'AC_ListScreenFactory::groups' );

		return AC_ListScreenFactory::groups();
	}

	/**
	 *
	 * @deprecated NEWVERSION
	 * @return AC_Groups
	 */
	public function column_groups() {
		_deprecated_function( __METHOD__, 'NEWVERSION' );

		return new AC_Groups();
	}

	/**
	 * Contains simple helper methods
	 *
	 * @since      3.0
	 * @deprecated NEWVERSION
	 *
	 * @return AC_Helper
	 */
	public function helper() {
		_deprecated_function( __METHOD__, '3.1.5', 'ac_helper()' );

		return ac_helper();
	}

}