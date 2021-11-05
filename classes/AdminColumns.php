<?php

namespace AC;

use AC\Admin;
use AC\Admin\AdminScripts;
use AC\Admin\PageRequestHandler;
use AC\Admin\Preference;
use AC\Admin\RequestHandler;
use AC\Admin\WpMenuFactory;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Controller;
use AC\Deprecated;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\InstallCollection;
use AC\Plugin\Installer;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Updater;
use AC\Plugin\Version;
use AC\Screen\QuickEdit;
use AC\Settings\GeneralOption;
use AC\Table;
use AC\ThirdParty;

class AdminColumns extends Plugin {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var self
	 */
	private static $instance;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	protected function __construct() {
		parent::__construct( AC_FILE, 'ac_version', new Version( AC_VERSION ) );

		$this->storage = new Storage();
		$this->storage->set_repositories( [
			'acp-database' => new ListScreenRepository\Storage\ListScreenRepository(
				new Database( ListScreenTypes::instance() ),
				true
			),
		] );

		$location = $this->get_location();

		$menu_factory = new Admin\MenuFactory( admin_url( 'options-general.php' ), $location );
		$head = new Admin\View\Menu( $menu_factory->create( filter_input( INPUT_GET, 'tab' ) ?: 'columns' ) );

		$page_factory_aggregate = new Admin\PageFactoryAggregate();
		$page_factory_aggregate->add( 'columns', new Admin\PageFactory\Columns( $this->storage, $location, $head ) )
		                       ->add( 'settings', new Admin\PageFactory\Settings( $location, $head ) )
		                       ->add( 'addons', new Admin\PageFactory\Addons( $location, new IntegrationRepository(), $head ) )
		                       ->add( 'help', new Admin\PageFactory\Help( $location, $head ) );

		RequestHandler::add_handler( new PageRequestHandler( $page_factory_aggregate, 'columns' ) );

		$services = [
			new Admin\Admin( new RequestHandler(), new WpMenuFactory(), new AdminScripts( $location ) ),
			new Admin\Notice\ReadOnly(),
			new Ajax\NumberFormat( new Request() ),
			new Deprecated\Hooks,
			new ListScreens(),
			new Screen,
			new ThirdParty\ACF,
			new ThirdParty\NinjaForms,
			new ThirdParty\WooCommerce,
			new ThirdParty\WPML( $this->storage ),
			new Controller\DefaultColumns( new Request(), new DefaultColumnsRepository() ),
			new QuickEdit( $this->storage, new Table\Preference() ),
			new Capabilities\Manage(),
			new Controller\AjaxColumnRequest( $this->storage, new Request() ),
			new Controller\AjaxGeneralOptions( new GeneralOption() ),
			new Controller\AjaxRequestCustomFieldKeys(),
			new Controller\AjaxColumnValue( $this->storage ),
			new Controller\AjaxScreenOptions( new Preference\ScreenOptions() ),
			new Controller\ListScreenRestoreColumns( $this->storage ),
			new Controller\RestoreSettingsRequest( $this->storage->get_repository( 'acp-database' ) ),
			new PluginActionLinks( $this->get_basename() ),
			new NoticeChecks( $location ),
			new Controller\TableListScreenSetter( $this->storage, new PermissionChecker(), $location, new Table\Preference() ),
		];

		$setup = is_multisite() && is_network_admin()
			? new Plugin\Setup( $this->get_version_storage(), $this->get_version(), $this->get_network_updater(), $this->get_installer() )
			: new Plugin\Setup( $this->get_version_storage(), $this->get_version(), $this->get_site_updater(), $this->get_installer() );

		$services[] = $setup;

		foreach ( $services as $service ) {
			if ( $service instanceof Registrable ) {
				$service->register();
			}
		}

		add_action( 'init', [ $this, 'register_global_scripts' ] );
	}

	private function get_network_updater() {
		return new Updater( new UpdateCollection( [] ), $this->get_version_storage() );
	}

	private function get_site_updater() {
		return new Updater(
			new UpdateCollection( [
				new Update\V3005(),
				new Update\V3007(),
				new Update\V3201(),
				new Update\V4000(),
			] ),
			$this->get_version_storage()
		);
	}

	private function get_installer() {
		return new Installer(
			new InstallCollection( [
				new Plugin\Install\Capabilities(),
				new Plugin\Install\Database(),
			] )
		);
	}

	/**
	 * @return Storage
	 */
	public function get_storage() {
		return $this->storage;
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
	 * @deprecated 4.3.1
	 */
	public function admin() {
		_deprecated_function( __METHOD__, '4.3.1' );
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
		_deprecated_function( __METHOD__, '4.1', 'ListScreenTypes::instance()->register_list_screen()' );

		ListScreenTypes::instance()->register_list_screen( $list_screen );

		return $this;
	}

}