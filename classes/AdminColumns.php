<?php

namespace AC;

use AC\Admin\GeneralSectionFactory;
use AC\Admin\Page;
use AC\Admin\Section\ListScreenMenu;
use AC\Admin\Section\Restore;
use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Controller\AjaxColumnValue;
use AC\Controller\AjaxRequestCustomFieldKeys;
use AC\Controller\AjaxRequestNewColumn;
use AC\Controller\ListScreenRequest;
use AC\Controller\ListScreenRestoreColumns;
use AC\Controller\RedirectAddonStatus;
use AC\Deprecated;
use AC\ListScreenRepository\Storage;
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
	 * @var ListScreenRepository\Storage
	 */
	private $storage;

	/**
	 * @since 2.5
	 * @var self
	 */
	private static $instance;

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
		$this->storage = new Storage();

		$this->register_admin();

		$services = [
			new Service\Storage( $this->storage, ListScreenTypes::instance() ),
			new Ajax\NumberFormat( new Request() ),
			new Deprecated\Hooks,
			new Screen,
			new Settings\General,
			new ThirdParty\ACF,
			new ThirdParty\NinjaForms,
			new ThirdParty\WooCommerce,
			new ThirdParty\WPML,
			new DefaultColumnsController( new Request(), new DefaultColumns() ),
			new QuickEdit( $this->storage, $this->preferences() ),
			new Capabilities\Manage(),
			new AjaxRequestNewColumn( $this->storage ),
			new AjaxRequestCustomFieldKeys(),
			new ListScreenRestoreColumns( $this->storage ),
			new AjaxColumnValue( $this->storage ),
			new ListScreenRestoreColumns( $this->storage ),
			new RedirectAddonStatus( $this->admin->get_url( Page\Addons::NAME ) ),
			new PluginActionLinks( $this->get_basename(), $this->admin->get_url( Page\Columns::NAME ) ),
			new NoticeChecks(),
			new TableLoader( $this->storage, new PermissionChecker(), $this->get_location() ),
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
	}

	/**
	 * @return ListScreenRepository\Storage
	 */
	public function get_storage() {
		return $this->storage;
	}

	/**
	 * @since 4.0.12
	 */
	public function preferences() {
		return new Preferences\Site( 'layout_table' );
	}

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

	private function get_location() {
		return new Absolute( $this->get_url(), $this->get_dir() );
	}

	public function register_global_scripts() {
		$assets = [
			new Script( 'ac-select2-core', $this->get_location()->with_suffix( 'assets/js/select2.js' ) ),
			new Script( 'ac-select2', $this->get_location()->with_suffix( 'assets/js/select2_conflict_fix.js' ), [ 'jquery', 'ac-select2-core' ] ),
			new Style( 'ac-select2', $this->get_location()->with_suffix( 'assets/css/select2.css' ) ),
			new Style( 'ac-jquery-ui', $this->get_location()->with_suffix( 'assets/css/ac-jquery-ui.css' ) ),
		];

		foreach ( $assets as $asset ) {
			$asset->register();
		}
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
		load_plugin_textdomain( 'codepress-admin-columns', false, $this->get_dir() . 'languages/' );
	}

	/**
	 * @return void
	 */
	private function register_admin() {
		$location = new Absolute( $this->get_url(), $this->get_dir() );

		$listscreen_controller = new ListScreenRequest( new Request(), $this->storage, new Preferences\Site( 'settings' ) );

		$this->admin = new Admin( 'options-general.php', 'admin_menu', admin_url() );

		$page_settings = new Page\Settings();
		$page_settings
			->register_section( GeneralSectionFactory::create() )
			->register_section( new Restore( new ListScreenRepository\DataBase( ListScreenTypes::instance() ) ) );

		$page_columns = new Page\Columns( $listscreen_controller, new ListScreenMenu( $listscreen_controller ), new UnitializedListScreens( new DefaultColumns() ) );

		$this->admin->register_page( $page_columns )
		            ->register_page( $page_settings )
		            ->register_page( new Page\Addons( $location ) )
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