<?php
declare( strict_types=1 );

namespace AC;

use AC\Admin;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Asset\Location\Absolute;
use AC\Asset\Script\Localize\Translation;
use AC\Controller;
use AC\Controller\RestoreSettingsRequest;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\Service;
use AC\Table;
use AC\Table\ListKeysFactoryInterface;
use AC\ThirdParty;
use AC\Type\Basename;
use AC\Vendor\DI;
use AC\Vendor\DI\ContainerBuilder;

class AdminColumns extends Plugin {

	public function __construct() {
		parent::__construct( AC_FILE, new Version( AC_VERSION ) );

		$container = $this->create_container();

		Container::set_container( $container );

        ListScreenFactory\Aggregate::add( $container->get( ListScreenFactory\UserFactory::class ) );
        ListScreenFactory\Aggregate::add( $container->get( ListScreenFactory\CommentFactory::class ) );
        ListScreenFactory\Aggregate::add( $container->get( ListScreenFactory\PostFactory::class ) );
        ListScreenFactory\Aggregate::add( $container->get( ListScreenFactory\MediaFactory::class ) );

		$page_handler = new PageRequestHandler();
		$page_handler->add( 'columns', $container->get( Admin\PageFactory\Columns::class ) )
		             ->add( 'settings', $container->get( Admin\PageFactory\Settings::class ) )
		             ->add( 'addons', $container->get( Admin\PageFactory\Addons::class ) )
		             ->add( 'help', $container->get( Admin\PageFactory\Help::class ) );

		PageRequestHandlers::add_handler( $page_handler );

		$this->create_services( $container )
		     ->register();
	}

	private function create_services( DI\Container $container ): Services {
		$services_fqn = [
			PluginActionLinks::class,
			Screen::class,
			Admin\Admin::class,
			Admin\Scripts::class,
			Admin\Notice\ReadOnlyListScreen::class,
			Ajax\NumberFormat::class,
			ThirdParty\ACF::class,
			ThirdParty\NinjaForms::class,
			ThirdParty\MediaLibraryAssistant\MediaLibraryAssistant::class,
			ThirdParty\WooCommerce::class,
			ThirdParty\WPML::class,
			Controller\DefaultColumns::class,
			Screen\QuickEdit::class,
			Capabilities\Manage::class,
			Controller\AjaxColumnRequest::class,
			Controller\AjaxGeneralOptions::class,
			Controller\AjaxRequestCustomFieldKeys::class,
			Controller\AjaxColumnModalValue::class,
			Controller\AjaxColumnValue::class,
			Controller\AjaxScreenOptions::class,
			Controller\ListScreenRestoreColumns::class,
			RestoreSettingsRequest::class,
			Controller\TableListScreenSetter::class,
			Service\IntegrationColumns::class,
			Service\CommonAssets::class,
			Service\Colors::class,
		];

		if ( ! defined( 'ACP_FILE' ) ) {
			$services_fqn[] = Service\NoticeChecks::class;
			$services_fqn[] = PluginActionUpgrade::class;
			$services_fqn[] = Service\ColumnsMockup::class;
		}

		$services = new Services( [
			new Service\Setup( $container->get( SetupFactory\AdminColumns::class )->create( SetupFactory::SITE ) ),
		] );

		foreach ( $services_fqn as $service_fqn ) {
			$services->add( $container->get( $service_fqn ) );
		}

		if ( $this->is_network_active() ) {
			$services->add( new Service\Setup( $container->get( SetupFactory\AdminColumns::class )->create( SetupFactory::NETWORK ) ) );
		}

		return $services;
	}

	private function create_container(): DI\Container {
		$definitions = [
			'translations.global'                   => function (): Translation {
				return new Translation( require $this->get_dir() . '/settings/translations/global.php' );
			},
			Database::class                         => DI\autowire()
				->constructorParameter( 0, new ListScreenFactory\Aggregate() ),
			Storage::class                          => static function ( Database $database ): Storage {
				$storage = new Storage();
				$storage->set_repositories( [
					'acp-database' => new ListScreenRepository\Storage\ListScreenRepository( $database, true ),
				] );

				return $storage;
			},
			RestoreSettingsRequest::class           => static function ( Storage $storage ): RestoreSettingsRequest {
				return new RestoreSettingsRequest( $storage->get_repository( 'acp-database' ) );
			},
			Absolute::class                         => DI\autowire()
				->constructorParameter( 0, $this->get_url() )
				->constructorParameter( 1, $this->get_dir() ),
			Basename::class                         => DI\autowire()
				->constructorParameter( 0, $this->get_basename() ),
			ListKeysFactoryInterface::class         => DI\autowire( Table\ListKeysFactory::class ),
			Service\CommonAssets::class             => DI\autowire()
				->constructorParameter( 1, DI\get( 'translations.global' ) ),
			Admin\Colors\Shipped\ColorParser::class => DI\autowire()
				->constructorParameter( 0, ABSPATH . 'wp-admin/css/common.css' ),
			Admin\Colors\ColorReader::class         => DI\autowire( Admin\Colors\ColorRepository::class ),
			Admin\Admin::class                      => DI\autowire()
				->constructorParameter( 0, DI\get( PageRequestHandlers::class ) ),
			Admin\MenuFactoryInterface::class       => DI\autowire( Admin\MenuFactory::class )
				->constructorParameter( 0, admin_url( 'options-general.php' ) ),
			Admin\MenuListFactory::class            => DI\autowire( Admin\MenuListFactory\MenuFactory::class ),
			Admin\PageFactory\Settings::class       => DI\autowire()
				->constructorParameter( 2, defined( 'ACP_FILE' ) ),
			SetupFactory\AdminColumns::class        => DI\autowire()
				->constructorParameter( 0, 'ac_version' )
				->constructorParameter( 1, $this->get_version() ),
			Service\Setup::class                    => DI\autowire()
				->constructorParameter( 0, DI\get( SetupFactory\AdminColumns::class ) ),
		];

		return ( new ContainerBuilder() )
			->addDefinitions( $definitions )
			->build();
	}

	private function is_network_active(): bool {
		return ( new PluginInformation( $this->get_basename() ) )->is_network_active();
	}

	public function get_storage(): Storage {
		return Container::get_storage();
	}

}
