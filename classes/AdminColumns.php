<?php

namespace AC;

use AC\Admin;
use AC\Admin\AdminScripts;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Admin\Preference;
use AC\Asset\Script\Localize\Translation;
use AC\Controller;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\Screen\QuickEdit;
use AC\Service;
use AC\Settings\GeneralOption;
use AC\Table;
use AC\ThirdParty;
use AC\Vendor\DI\ContainerBuilder;

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

		$plugin_information = new PluginInformation( $this->get_basename() );
		$is_network_active = $plugin_information->is_network_active();
		$is_acp_active = $this->is_acp_active();

		$this->storage = new Storage();
		$this->storage->set_repositories( [
			'acp-database' => new ListScreenRepository\Storage\ListScreenRepository(
				new Database( ListScreenTypes::instance() ),
				true
			),
		] );

		// TODO Or we can just have the object Translation defined and not (yet) have a container in AC
		$definitions = [
			'translations.global' => function (): Translation {
				return new Translation( require $this->get_dir() . '/settings/translations/global.php' );
			},
		];

		$container = ( new ContainerBuilder() )
			->addDefinitions( $definitions )
			->build();

		/** @var Translation $global_translations */
		$global_translations = $container->get( 'translations.global' );

		$location = $this->get_location();
		$menu_factory = new Admin\MenuFactory( admin_url( 'options-general.php' ), $location );

		$page_handler = new PageRequestHandler();
		$page_handler->add( 'columns', new Admin\PageFactory\Columns( $this->storage, $location, $menu_factory, $is_acp_active ) )
		             ->add( 'settings', new Admin\PageFactory\Settings( $location, $menu_factory, $is_acp_active ) )
		             ->add( 'addons', new Admin\PageFactory\Addons( $location, new IntegrationRepository(), $menu_factory ) )
		             ->add( 'help', new Admin\PageFactory\Help( $location, $menu_factory ) );

		PageRequestHandlers::add_handler( $page_handler );

		$color_repository = new Admin\Colors\ColorRepository( new Admin\Colors\Storage\OptionFactory() );

		$services = [
			new Admin\Admin( new PageRequestHandlers(), $location, new AdminScripts( $location ) ),
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
			new Controller\AjaxColumnModalValue( $this->storage ),
			new Controller\AjaxColumnValue( $this->storage ),
			new Controller\AjaxScreenOptions( new Preference\ScreenOptions() ),
			new Controller\ListScreenRestoreColumns( $this->storage ),
			new Controller\RestoreSettingsRequest( $this->storage->get_repository( 'acp-database' ) ),
			new PluginActionLinks( $this->get_basename(), $is_acp_active ),
			new Controller\TableListScreenSetter( $this->storage, new PermissionChecker(), $location, new Table\LayoutPreference() ),
			new Admin\Scripts( $location ),
			new Service\IntegrationColumns( new IntegrationRepository() ),
			new Service\CommonAssets( $location, $global_translations ),
			new Service\Colors(
				new Admin\Colors\Shipped\ColorUpdater(
					new Admin\Colors\Shipped\ColorParser( ABSPATH . 'wp-admin/css/common.css' ),
					$color_repository,
					new Admin\Colors\Storage\OptionFactory()
				),
				new Admin\Colors\StyleInjector( $color_repository )
			),
		];

		if ( ! $is_acp_active ) {
			$services[] = new Service\NoticeChecks( $location );
		}

		$setup_factory = new SetupFactory\AdminColumns( 'ac_version', $this->get_version() );

		$services[] = new Service\Setup( $setup_factory->create( SetupFactory::SITE ) );

		if ( $is_network_active ) {
			$services[] = new Service\Setup( $setup_factory->create( SetupFactory::NETWORK ) );
		}

		array_map( static function ( Registerable $service ) {
			$service->register();
		}, $services );
	}

	private function is_acp_active(): bool {
		return defined( 'ACP_FILE' );
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
