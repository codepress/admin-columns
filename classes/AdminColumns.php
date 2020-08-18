<?php

namespace AC;

use AC\Admin\Page;
use AC\Admin\Preference;
use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Controller;
use AC\Deprecated;
use AC\ListScreenRepository\Database;
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
	 * @var Storage
	 */
	private $storage;

	/**
	 * @since 2.5
	 * @var self
	 */
	private static $instance;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		$this->storage = new Storage();
		$this->storage->set_repositories( [
			'acp-database' => new ListScreenRepository\Storage\ListScreenRepository(
				new Database( ListScreenTypes::instance() ),
				true
			),
		] );

		$location = new Absolute(
			$this->get_url(),
			$this->get_dir()
		);

		$this->admin = ( new AdminFactory( $this->storage, $location ) )->create();

		$services = [
			$this->admin,
			new Ajax\NumberFormat( new Request() ),
			new Deprecated\Hooks,
			new ListScreens(),
			new Screen,
			new Settings\General,
			new ThirdParty\ACF,
			new ThirdParty\NinjaForms,
			new ThirdParty\WooCommerce,
			new ThirdParty\WPML,
			new Controller\DefaultColumns( new Request(), new DefaultColumnsRepository() ),
			new QuickEdit( $this->storage, new Table\Preference() ),
			new Capabilities\Manage(),
			new Controller\AjaxColumnRequest( $this->storage, new Request() ),
			new Controller\AjaxRequestCustomFieldKeys(),
			new Controller\AjaxColumnValue( $this->storage ),
			new Controller\AjaxScreenOptions( new Preference\ScreenOptions() ),
			new Controller\ListScreenRestoreColumns( $this->storage ),
			new Controller\RedirectAddonStatus( ac_get_admin_url( Page\Addons::NAME ), new Integrations() ),
			new Controller\RestoreSettingsRequest( $this->storage->get_repository( 'acp-database' ) ),
			new PluginActionLinks( $this->get_basename() ),
			new NoticeChecks(),
			new TableLoader( $this->storage, new PermissionChecker(), $location, new Table\Preference() ),
		];

		foreach ( $services as $service ) {
			if ( $service instanceof Registrable ) {
				$service->register();
			}
		}

		$this->set_installer( new Plugin\Installer() );

		add_action( 'init', [ $this, 'install' ], 1000 );
		add_action( 'init', [ $this, 'register_global_scripts' ] );
	}

	/**
	 * @return Storage
	 */
	public function get_storage() {
		return $this->storage;
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

	public function admin() {
		return $this->admin;
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
	 * @param $file
	 *
	 * @since      3.0
	 * @deprecated 3.1.5
	 */
	public function get_plugin_version( $file ) {
		_deprecated_function( __METHOD__, '3.1.5' );
	}

	/**
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
	 * @return ListScreen|null
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
	 * @return Helper
	 * @deprecated 3.2
	 * @since      3.0
	 */
	public function helper() {
		_deprecated_function( __METHOD__, '3.2', 'ac_helper()' );

		return new Helper();
	}

	/**
	 * @deprecated 3.4
	 */
	public function table_screen() {
		_deprecated_function( __METHOD__, '3.4' );
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

	/**
	 * @return bool
	 * @deprecated 4.1
	 * @since      2.5
	 */
	public function use_delete_confirmation() {
		_deprecated_function( __METHOD__, '4.1' );

		return (bool) apply_filters( 'ac/delete_confirmation', true );
	}

	/**
	 * @return bool
	 * @deprecated 4.1
	 */
	public function is_doing_ajax() {
		_deprecated_function( __METHOD__, '4.1', 'wp_doing_ajax()' );

		return wp_doing_ajax();
	}

	/**
	 * @return array
	 * @since      1.0
	 * @deprecated 4.1
	 */
	public function get_post_types() {
		_deprecated_function( __METHOD__, '4.1' );

		return ( new ListScreens )->get_post_types();
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return self
	 * @deprecated 4.1
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		_deprecated_function( __METHOD__, '4.1', 'ListScreenTypes::register_list_screen()' );

		ListScreenTypes::instance()->register_list_screen( $list_screen );

		return $this;
	}

}