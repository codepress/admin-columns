<?php

namespace AC;

use AC\Admin;
use AC\Admin\AdminScripts;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Asset\Script\Localize\Translation;
use AC\Controller;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\Screen\QuickEdit;
use AC\Service;
use AC\Table;
use AC\ThirdParty;
use AC\Vendor\DI;
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

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	protected function __construct() {
		parent::__construct( AC_FILE, new Version( AC_VERSION ) );

		$plugin_information = new PluginInformation( $this->get_basename() );
		$is_network_active = $plugin_information->is_network_active();
		$is_acp_active = defined( 'ACP_FILE' );

		$definitions = [
			'translations.global'                    => function (): Translation {
				return new Translation( require $this->get_dir() . '/settings/translations/global.php' );
			},
			Database::class                          => static function (): Database {
				return new Database( new ListScreenFactory() );
			},
			Storage::class                           => static function ( Database $database ): Storage {
				$storage = new Storage();
				$storage->set_repositories( [
					'acp-database' => new ListScreenRepository\Storage\ListScreenRepository( $database, true ),
				] );

				return $storage;
			},
			Controller\RestoreSettingsRequest::class => static function ( Storage $storage ) {
				return new Controller\RestoreSettingsRequest( $storage->get_repository( 'acp-database' ) );
			},
			PluginActionLinks::class                 => DI\autowire()
				->constructorParameter( 0, $this->get_basename() )
				->constructorParameter( 1, $is_acp_active ),
			Controller\TableListScreenSetter::class  => DI\autowire()
				->constructorParameter( 1, $this->get_location() ),
			Admin\Scripts::class                     => DI\autowire()
				->constructorParameter( 0, $this->get_location() ),
			Service\CommonAssets::class              => DI\autowire()
				->constructorParameter( 0, $this->get_location() )
				->constructorParameter( 1, DI\get( 'translations.global' ) ),
			Admin\Colors\Shipped\ColorParser::class  => DI\autowire()
				->constructorParameter( 0, ABSPATH . 'wp-admin/css/common.css' ),
			Admin\Colors\ColorReader::class          => DI\autowire( Admin\Colors\ColorRepository::class ),
			AdminScripts::class                      => DI\autowire()
				->constructorParameter( 0, $this->get_location() ),
			Admin\Admin::class                       => DI\autowire()
				->constructorParameter( 0, DI\get( PageRequestHandlers::class ) )
				->constructorParameter( 1, $this->get_location() ),
			Service\NoticeChecks::class              => DI\autowire()
				->constructorParameter( 0, $this->get_location() ),
			Admin\MenuFactory::class                 => DI\autowire()
				->constructorParameter( 0, admin_url( 'options-general.php' ) )
				->constructorParameter( 1, $this->get_location() ),
			Table\ListKeysFactoryInterface::class    => DI\autowire( Table\ListKeysFactory::class ),
			Admin\MenuListFactory::class             => DI\autowire( Admin\MenuListFactory\MenuFactory::class ),
			Admin\PageFactory\Columns::class         => DI\autowire()
				->constructorParameter( 1, $this->get_location() )
				->constructorParameter( 2, DI\get( Admin\MenuFactory::class ) )
				->constructorParameter( 7, $is_acp_active ),
			Admin\PageFactory\Settings::class        => DI\autowire()
				->constructorParameter( 0, $this->get_location() )
				->constructorParameter( 1, DI\get( Admin\MenuFactory::class ) )
				->constructorParameter( 2, $is_acp_active ),
			Admin\PageFactory\Addons::class          => DI\autowire()
				->constructorParameter( 0, $this->get_location() )
				->constructorParameter( 2, DI\get( Admin\MenuFactory::class ) ),
			Admin\PageFactory\Help::class            => DI\autowire()
				->constructorParameter( 0, $this->get_location() )
				->constructorParameter( 1, DI\get( Admin\MenuFactory::class ) ),
			SetupFactory\AdminColumns::class         => DI\autowire()
				->constructorParameter( 0, 'ac_version' )
				->constructorParameter( 1, $this->get_version() ),
		];

		$container = ( new ContainerBuilder() )
			->addDefinitions( $definitions )
			->build();

		$this->storage = $container->get( Storage::class );

		ListScreenFactory::add( $container->get( ListScreenFactory\UserFactory::class ) );
		ListScreenFactory::add( $container->get( ListScreenFactory\CommentFactory::class ) );
		ListScreenFactory::add( $container->get( ListScreenFactory\PostFactory::class ) );
		ListScreenFactory::add( $container->get( ListScreenFactory\MediaFactory::class ) );

		$page_handler = new PageRequestHandler();
		$page_handler->add( 'columns', $container->get( Admin\PageFactory\Columns::class ) )
		             ->add( 'settings', $container->get( Admin\PageFactory\Settings::class ) )
		             ->add( 'addons', $container->get( Admin\PageFactory\Addons::class ) )
		             ->add( 'help', $container->get( Admin\PageFactory\Help::class ) );

		PageRequestHandlers::add_handler( $page_handler );

		$services_fqn = [
			Admin\Admin::class,
			Admin\Notice\ReadOnlyListScreen::class,
			Ajax\NumberFormat::class,
			Screen::class,
			ThirdParty\ACF::class,
			ThirdParty\NinjaForms::class,
			ThirdParty\MediaLibraryAssistant\MediaLibraryAssistant::class,
			ThirdParty\WooCommerce::class,
			ThirdParty\WPML::class,
			Controller\DefaultColumns::class,
			QuickEdit::class,
			Capabilities\Manage::class,
			Controller\AjaxColumnRequest::class,
			Controller\AjaxGeneralOptions::class,
			Controller\AjaxRequestCustomFieldKeys::class,
			Controller\AjaxColumnModalValue::class,
			Controller\AjaxColumnValue::class,
			Controller\AjaxScreenOptions::class,
			Controller\ListScreenRestoreColumns::class,
			Controller\RestoreSettingsRequest::class,
			PluginActionLinks::class,
			Controller\TableListScreenSetter::class,
			Admin\Scripts::class,
			Service\IntegrationColumns::class,
			Service\CommonAssets::class,
			Service\Colors::class,
		];

		if ( ! $is_acp_active ) {
			$services_fqn[] = Service\NoticeChecks::class;
		}

		array_map( static function ( string $service ) use ( $container ): void {
			$container->get( $service )->register();
		}, $services_fqn );

		$services[] = new Service\Setup( $container->get( SetupFactory\AdminColumns::class )->create( SetupFactory::SITE ) );

		if ( $is_network_active ) {
			$services[] = new Service\Setup( $container->get( SetupFactory\AdminColumns::class )->create( SetupFactory::NETWORK ) );
		}

		array_map( static function ( Registerable $service ) {
			$service->register();
		}, $services );
	}

	public function get_storage(): Storage {
		return $this->storage;
	}

}
