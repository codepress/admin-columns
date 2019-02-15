<?php

namespace AC;

use AC\Admin\GeneralSectionFactory;
use AC\Admin\Page;
use AC\Admin\Section\Restore;
use AC\Check;
use AC\Deprecated;
use AC\Screen\QuickEdit;
use AC\Table;
use AC\ThirdParty;

class AdminColumns extends Plugin {

	/**
	 * @var Admin
	 */
	private $admin;

	/**
	 * @var Table\Screen
	 */
	private $table_screen;

	/**
	 * @var API
	 */
	private $api;

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
		$this->api = new API();

		$modules = array(
			new Deprecated\Hooks,
			new QuickEdit(),
			new Screen,
			new Settings\General,
			new ThirdParty\ACF,
			new ThirdParty\NinjaForms,
			new ThirdParty\WooCommerce,
			new ThirdParty\WPML,
		);

		foreach ( $modules as $module ) {
			if ( $module instanceof Registrable ) {
				$module->register();
			}
		}

		$this->register_admin();

		add_action( 'init', array( $this, 'init_capabilities' ) );
		add_action( 'init', array( $this, 'install' ) );
		add_action( 'init', array( $this, 'notice_checks' ) );

		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
		add_action( 'plugins_loaded', array( $this, 'localize' ) );

		add_action( 'ac/screen', array( $this, 'init_table_on_screen' ) );
		add_action( 'ac/screen/quick_edit', array( $this, 'init_table_on_quick_edit' ) );
		add_action( 'wp_ajax_ac_get_column_value', array( $this, 'table_ajax_value' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'add_global_javascript_var' ), 1 );

		add_filter( 'wp_redirect', array( $this, 'redirect_after_status_change' ) );
	}

	/**
	 * @param Screen $screen
	 */
	public function init_table_on_screen( Screen $screen ) {
		$list_screen = $screen->get_list_screen();

		if ( ! $list_screen instanceof ListScreen ) {
			return;
		}

		$table_screen = new Table\Screen( $list_screen );
		$table_screen->register();

		do_action( 'ac/table', $table_screen );

		$this->table_screen = $table_screen;
	}

	/**
	 * @param Screen\QuickEdit $screen
	 */
	public function init_table_on_quick_edit( Screen\QuickEdit $screen ) {
		$list_screen = $screen->get_list_screen();

		if ( $list_screen instanceof ListScreen ) {
			new ScreenController( $list_screen );
		}
	}

	/**
	 * Get column value by ajax.
	 */
	public function table_ajax_value() {
		check_ajax_referer( 'ac-ajax' );

		// Get ID of entry to edit
		$id = intval( filter_input( INPUT_POST, 'pk' ) );

		if ( ! $id ) {
			wp_die( __( 'Invalid item ID.', 'codepress-admin-columns' ), null, 400 );
		}

		$list_screen = ListScreenFactory::create( filter_input( INPUT_POST, 'list_screen' ), filter_input( INPUT_POST, 'layout' ) );

		if ( ! $list_screen ) {
			wp_die( __( 'Invalid list screen.', 'codepress-admin-columns' ), null, 400 );
		}

		$column = $list_screen->get_column_by_name( filter_input( INPUT_POST, 'column' ) );

		if ( ! $column ) {
			wp_die( __( 'Invalid column.', 'codepress-admin-columns' ), null, 400 );
		}

		if ( ! $column instanceof Column\AjaxValue ) {
			wp_die( __( 'Invalid method.', 'codepress-admin-columns' ), null, 400 );
		}

		// Trigger ajax callback
		echo $column->get_ajax_value( $id );
		exit;
	}

	/**
	 * Init checks
	 */
	public function notice_checks() {
		$checks = array(
			new Check\Review(),
		);

		foreach ( new Integrations() as $integration ) {
			$checks[] = new Check\AddonAvailable( $integration );
		}

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
		return AC_VERSION;
	}

	/**
	 * Initialize current user and make sure any administrator user can use Admin Columns
	 * @since 3.2
	 */
	public function init_capabilities() {
		$caps = new Capabilities();

		if ( ! $caps->is_administrator() || $caps->has_manage() ) {
			return;
		}

		add_action( 'admin_init', array( $caps, 'add_manage' ) );
	}

	/**
	 * Add a settings link to the Admin Columns entry in the plugin overview screen
	 * @since 1.0
	 * @see   filter:plugin_action_links
	 *
	 * @param array  $links
	 * @param string $file
	 *
	 * @return array
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file === $this->get_basename() ) {
			array_unshift( $links, sprintf( '<a href="%s">%s</a>', $this->admin->get_url( 'columns' ), __( 'Settings', 'codepress-admin-columns' ) ) );
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
	 *
	 * @return self
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		$this->list_screens[ $list_screen->get_key() ] = $list_screen;

		return $this;
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
	 * @since 1.0
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
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'ac/post_types', $post_types );
	}

	/**
	 * Load text-domain
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, $this->get_dir() . '/languages/' );
	}

	/**
	 * Add a global JS var that ideally contains all AC and ACP API methods
	 */
	public function add_global_javascript_var() {
		?>
		<script>
			var AdminColumns = {};
		</script>
		<?php
	}

	/**
	 * @return void
	 */
	private function register_admin() {
		$is_network = is_network_admin();

		$site_factory = new Admin\AdminFactory();
		$this->admin = $site_factory->create( $is_network );

		if ( ! $is_network ) {

			$page_settings = new Page\Settings();
			$page_settings
				->register_section( GeneralSectionFactory::create() )
				->register_section( new Restore() );

			$page_columns = new Page\Columns();
			$page_columns->register_ajax();

			$this->admin->register_page( $page_columns )
			            ->register_page( $page_settings )
			            ->register_page( new Page\Addons() )
			            ->register_page( new Page\Help() )
			            ->register();
		}
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 * @since 2.2
	 *
	 * @param $location
	 *
	 * @return string
	 */
	public function redirect_after_status_change( $location ) {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow || ! filter_input( INPUT_GET, 'ac-redirect' ) || filter_input( INPUT_GET, 'error' ) ) {
			return $location;
		}

		$status = filter_input( INPUT_GET, 'action' );

		if ( ! $status ) {
			return $location;
		}

		$integration = IntegrationFactory::create( filter_input( INPUT_GET, 'plugin' ) );

		if ( ! $integration ) {
			return $location;
		}

		$location = add_query_arg( array(
			'status'    => $status,
			'plugin'    => $integration->get_slug(),
			'_ac_nonce' => wp_create_nonce( 'ac-plugin-status-change' ),
		), $this->admin()->get_url( 'addons' ) );

		return $location;
	}

	/**
	 * @deprecated 3.1.5
	 * @since      3.0
	 *
	 * @param $file
	 */
	public function get_plugin_version( $file ) {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

	/**
	 * Returns the default list screen when no choice is made by the user
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
	 * @deprecated 3.2
	 *
	 * @param string $key
	 *
	 * @return ListScreen|false
	 */
	public function get_list_screen( $key ) {
		_deprecated_function( __METHOD__, '3.2', 'ListScreenFactory::create()' );

		return ListScreenFactory::create( $key );
	}

	/**
	 * @param string $key
	 *
	 * @deprecated 3.2
	 * @return bool
	 */
	public function list_screen_exists( $key ) {
		_deprecated_function( __METHOD__, '3.2' );

		return ListScreenFactory::create( $key ) ? true : false;
	}

	/**
	 * @deprecated 3.2
	 * @return Groups
	 */
	public function list_screen_groups() {
		_deprecated_function( __METHOD__, '3.1.5', 'ListScreenGroups::get_groups' );

		return ListScreenGroups::get_groups();
	}

	/**
	 * @deprecated 3.2
	 * @return Groups
	 */
	public function column_groups() {
		_deprecated_function( __METHOD__, '3.2' );

		return new Groups();
	}

	/**
	 * Contains simple helper methods
	 * @since      3.0
	 * @deprecated 3.2
	 * @return Helper
	 */
	public function helper() {
		_deprecated_function( __METHOD__, '3.2', 'ac_helper()' );

		return ac_helper();
	}

	/**
	 * @return Table\Screen Returns the screen manager for the list table
	 * @deprecated 3.4
	 */
	public function table_screen() {
		_deprecated_function( __METHOD__, '3.4' );

		return $this->table_screen;
	}

	/**
	 * @deprecated 3.4
	 * @return Admin\Page\Columns
	 */
	public function admin_columns_screen() {
		_deprecated_function( __METHOD__, '3.4' );

		return new Admin\Page\Columns();
	}

}