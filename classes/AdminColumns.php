<?php

namespace AC;

use AC\Admin\GeneralSectionFactory;
use AC\Admin\Page;
use AC\Admin\Section\ListScreenMenu;
use AC\Admin\Section\Restore;
use AC\Controller\AjaxColumnValue;
use AC\Controller\AjaxRequestCustomFieldKeys;
use AC\Controller\AjaxRequestNewColumn;
use AC\Controller\ListScreenRequest;
use AC\Controller\ListScreenRestoreColumns;
use AC\Controller\RedirectAddonStatus;
use AC\Deprecated;
use AC\ListScreenRepository;
use AC\ListScreenRepository\FilterStrategy;
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

	/** @var ListScreenRepository\Aggregate */
	private $list_screen_repository;

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

		$this->list_screen_repository = new ListScreenRepository\Aggregate();
		$this->list_screen_repository->register_repository( new ListScreenRepository\DataBase( ListScreenTypes::instance() ) );

		$this->register_admin();

		$services = [
			new Ajax\NumberFormat( new Request() ),
			new Deprecated\Hooks,
			new Screen,
			new Settings\General,
			new ThirdParty\ACF,
			new ThirdParty\NinjaForms,
			new ThirdParty\WooCommerce,
			new ThirdParty\WPML,
			new DefaultColumnsController( new Request(), new DefaultColumns() ),
			new QuickEdit( $this->list_screen_repository, $this->preferences() ),
			new Capabilities\Manage(),
			new AjaxRequestNewColumn( $this->list_screen_repository ),
			new AjaxRequestCustomFieldKeys(),
			new AjaxColumnValue( $this->list_screen_repository ),
			new ListScreenRestoreColumns( $this->list_screen_repository ),
			new RedirectAddonStatus( $this->admin->get_url( Page\Addons::NAME ) ),
			new PluginActionLinks( $this->get_basename(), $this->admin->get_url( Page\Columns::NAME ) ),
			new NoticeChecks(),
		];

		foreach ( $services as $service ) {
			if ( $service instanceof Registrable ) {
				$service->register();
			}
		}

		$this->localize();

		add_action( 'init', [ $this, 'register_list_screens' ], 1000 ); // run after all post types are registered
		add_action( 'init', [ $this, 'install' ], 1000 );
		add_action( 'init', [ $this, 'register_global_scripts' ] );
		add_action( 'ac/screen', [ $this, 'init_table_on_screen' ] );
	}

	/**
	 * @return ListScreenRepository\Aggregate
	 */
	public function get_listscreen_repository() {
		return $this->list_screen_repository;
	}

	/**
	 * @since 4.0.12
	 */
	public function preferences() {
		return new Preferences\Site( 'layout_table' );
	}

	/**
	 * @param Screen $screen
	 */
	public function init_table_on_screen( Screen $screen ) {
		$key = $screen->get_list_screen();

		if ( ! $key ) {
			return;
		}

		// Requested
		$list_id = filter_input( INPUT_GET, 'layout' );

		// Last visited
		if ( ! $list_id ) {
			$list_id = $this->preferences()->get( $key );
		}

		$list_screen = null;
		$permission_checker = ( new PermissionChecker( wp_get_current_user() ) );

		if ( $list_id ) {
			$requested_list_screen = $this->list_screen_repository->find( $list_id );

			if ( $requested_list_screen && $permission_checker->is_valid( $requested_list_screen ) ) {
				$list_screen = $requested_list_screen;
			}
		}

		// First visit or not found
		if ( ! $list_screen ) {
			$list_screen = $this->get_first_list_screen( $key, $permission_checker );
		}

		$this->preferences()->set( $key, $list_screen->get_layout_id() );

		$table_screen = new Table\Screen( $list_screen );
		$table_screen->register();

		do_action( 'ac/table', $table_screen );

		$this->table_screen = $table_screen;
	}

	/**
	 * @param string            $key
	 * @param PermissionChecker $permission_checker
	 *
	 * @return ListScreen|null
	 */
	private function get_first_list_screen( $key, PermissionChecker $permission_checker ) {
		$list_screens = $this->list_screen_repository->find_all( [
			'key'    => $key,
			'filter' => new FilterStrategy\ByPermission( $permission_checker ),
		] );

		if ( $list_screens->count() > 0 ) {

			// First visit. Load first available list Id.
			return $list_screens->current();
		}

		// No available list screen found.
		return ListScreenTypes::instance()->get_list_screen_by_key( $key );
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
	 * @since 2.5
	 */
	public function use_delete_confirmation() {
		return apply_filters( 'ac/delete_confirmation', true );
	}

	/**
	 * @return Admin Settings class instance
	 * @since 2.2
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
	 * @param ListScreen $list_screen
	 *
	 * @return self
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		ListScreenTypes::instance()->register_list_screen( $list_screen );

		return $this;
	}

	public function register_list_screens() {
		$list_screens = [];

		foreach ( $this->get_post_types() as $post_type ) {
			$list_screens[] = new ListScreen\Post( $post_type );
		}

		$list_screens[] = new ListScreen\Media();
		$list_screens[] = new ListScreen\Comment();

		if ( ! is_multisite() ) {
			$list_screens[] = new ListScreen\User();
		}

		foreach ( $list_screens as $list_screen ) {
			ListScreenTypes::instance()->register_list_screen( $list_screen );
		}

		do_action( 'ac/list_screens', $this );
	}

	/**
	 * @return void
	 */
	public function register_global_scripts() {
		wp_register_script( 'ac-select2-core', $this->get_url() . 'assets/js/select2.js', [], $this->get_version() );
		wp_register_script( 'ac-select2', $this->get_url() . 'assets/js/select2_conflict_fix.js', [ 'jquery', 'ac-select2-core' ], $this->get_version() );
		wp_register_style( 'ac-select2', $this->get_url() . 'assets/css/select2.css', [], $this->get_version() );
		wp_register_style( 'ac-jquery-ui', $this->get_url() . 'assets/css/ac-jquery-ui.css', [], $this->get_version() );
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 * @return array List of post type keys (e.g. post, page)
	 * @since 1.0
	 */
	public function get_post_types() {
		$post_types = get_post_types( [
			'_builtin' => false,
			'show_ui'  => true,
		] );

		foreach ( [ 'post', 'page' ] as $builtin ) {
			if ( post_type_exists( $builtin ) ) {
				$post_types[ $builtin ] = $builtin;
			}
		}

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @param array $post_types List of active post type names
		 *
		 * @since 2.0
		 */
		return apply_filters( 'ac/post_types', $post_types );
	}

	/**
	 * Load text-domain
	 */
	public function localize() {
		$relative_dir = str_replace( WP_PLUGIN_DIR, '', $this->get_dir() );

		load_plugin_textdomain( 'codepress-admin-columns', false, $relative_dir . 'languages/' );
	}

	/**
	 * @return void
	 */
	private function register_admin() {
		$listscreen_controller = new ListScreenRequest( new Request(), $this->list_screen_repository, new Preferences\Site( 'settings' ) );

		$this->admin = new Admin( 'options-general.php', 'admin_menu', admin_url() );

		$page_settings = new Page\Settings();
		$page_settings
			->register_section( GeneralSectionFactory::create() )
			->register_section( new Restore( new ListScreenRepository\DataBase( ListScreenTypes::instance() ) ) );

		$page_columns = new Page\Columns( $listscreen_controller, new ListScreenMenu( $listscreen_controller ), new UnitializedListScreens( new DefaultColumns() ) );

		$this->admin->register_page( $page_columns )
		            ->register_page( $page_settings )
		            ->register_page( new Page\Addons() )
		            ->register_page( new Page\Help() )
		            ->register();
	}

	/**
	 * @param $file
	 *
	 * @since      3.0
	 * @deprecated 3.1.5
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
	 * @param string $key
	 *
	 * @return ListScreen|false
	 * @since      3.0
	 * @deprecated 3.2
	 */
	public function get_list_screen( $key ) {
		_deprecated_function( __METHOD__, '3.2', 'ListScreenTypes::instance()->get_list_screen_by_key()' );

		return ListScreenTypes::instance()->get_list_screen_by_key( $key );
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 * @deprecated 3.2
	 */
	public function list_screen_exists( $key ) {
		_deprecated_function( __METHOD__, '3.2' );

		return ListScreenTypes::instance()->get_list_screen_by_key( $key ) ? true : false;
	}

	/**
	 * @return Groups
	 * @deprecated 3.2
	 */
	public function list_screen_groups() {
		_deprecated_function( __METHOD__, '3.1.5', 'ListScreenGroups::get_groups' );

		return ListScreenGroups::get_groups();
	}

	/**
	 * @return Groups
	 * @deprecated 3.2
	 */
	public function column_groups() {
		_deprecated_function( __METHOD__, '3.2' );

		return new Groups();
	}

	/**
	 * Contains simple helper methods
	 * @return Helper
	 * @deprecated 3.2
	 * @since      3.0
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
	 */
	public function admin_columns_screen() {
		_deprecated_function( __METHOD__, '3.4' );
	}

	/**
	 * @since      3.0
	 * @deprecated 4.0
	 */
	public function api() {
		_deprecated_function( __METHOD__, '4.0' );
	}

	/**
	 * @return ListScreen[]
	 * @deprecated 4.0
	 */
	public function get_list_screens() {
		_deprecated_function( __METHOD__, '4.0', 'ListScreenTypes::instance()->get_list_screens()' );

		return ListScreenTypes::instance()->get_list_screens();
	}

}