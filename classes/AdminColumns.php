<?php

namespace AC;

use AC\Admin\AdminScripts;
use AC\Admin\MenuFactory;
use AC\Admin\Page\Columns;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\Preference;
use AC\Admin\RequestHandler;
use AC\Admin\WpMenuFactory;
use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Controller;
use AC\Deprecated;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\InstallCollection;
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

	protected function __construct() {
		parent::__construct( AC_FILE, 'ac_version', new Version( AC_VERSION ) );

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

		RequestHandler::add_handler(
			new PageRequestHandler(
				new PageFactory( $this->storage, $location, new MenuFactory( admin_url( 'options-general.php' ), new IntegrationRepository() ) ),
				Columns::NAME
			)
		);

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
			new NoticeChecks( $this->get_location() ),
			new Controller\TableListScreenSetter( $this->storage, new PermissionChecker(), $location, new Table\Preference() ),
		];

		foreach ( $services as $service ) {
			if ( $service instanceof Registrable ) {
				$service->register();
			}
		}

		$installer = new InstallCollection();
		$installer->add_install( new Plugin\Install\Capabilities() )
		          ->add_install( new Plugin\Install\Database() );

		$this->set_installer( $installer );

		add_action( 'init', [ $this, 'install' ], 1000 );
		add_action( 'init', [ $this, 'register_global_scripts' ] );
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