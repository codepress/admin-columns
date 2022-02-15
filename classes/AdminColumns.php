<?php

namespace AC;

use AC\Admin;
use AC\Admin\AdminScripts;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Admin\Preference;
use AC\Admin\WpMenuFactory;
use AC\Controller;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\InstallCollection;
use AC\Plugin\SetupFactory;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Screen\QuickEdit;
use AC\Service;
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
		parent::__construct( AC_FILE, new Version( AC_VERSION ) );

		$this->storage = new Storage();
		$this->storage->set_repositories( [
			'acp-database' => new ListScreenRepository\Storage\ListScreenRepository(
				new Database( ListScreenTypes::instance() ),
				true
			),
		] );

		$location = $this->get_location();
		$menu_factory = new Admin\MenuFactory( admin_url( 'options-general.php' ), $location );

		$page_handler = new PageRequestHandler();
		$page_handler->add( 'columns', new Admin\PageFactory\Columns( $this->storage, $location, $menu_factory ) )
		             ->add( 'settings', new Admin\PageFactory\Settings( $location, $menu_factory ) )
		             ->add( 'addons', new Admin\PageFactory\Addons( $location, new IntegrationRepository(), $menu_factory ) )
		             ->add( 'help', new Admin\PageFactory\Help( $location, $menu_factory ) );

		PageRequestHandlers::add_handler( $page_handler );

		$services = [
			new Admin\Admin( new PageRequestHandlers(), new WpMenuFactory(), new AdminScripts( $location ) ),
			new Admin\Notice\ReadOnlyListScreen(),
			new Ajax\NumberFormat( new Request() ),
			new ListScreens(),
			new Screen(),
			new ThirdParty\ACF(),
			new ThirdParty\NinjaForms(),
			new ThirdParty\WooCommerce(),
			new ThirdParty\WPML( $this->storage ),
			new Controller\DefaultColumns( new Request(), new DefaultColumnsRepository() ),
			new QuickEdit( $this->storage, new Table\LayoutPreference() ),
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
			new Controller\TableListScreenSetter( $this->storage, new PermissionChecker(), $location, new Table\LayoutPreference() ),
			new Admin\Scripts( $location ),
		];

		$setup_factory = new SetupFactory(
			'ac_version',
			$this->get_version(),
			false,
			new InstallCollection( [
				new Plugin\Install\Capabilities(),
				new Plugin\Install\Database(),
			] ),
			new UpdateCollection([
				new Plugin\Update\V3005(),
				new Plugin\Update\V3007(),
				new Plugin\Update\V3201(),
				new Plugin\Update\V4000(),
			] )
		);

		$services[] = new Service\Setup( $setup_factory->create() );

		array_map( static function ( Registrable $service ) {
			$service->register();
		}, $services );
	}

	/**
	 * @return Storage
	 */
	public function get_storage() {
		return $this->storage;
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
