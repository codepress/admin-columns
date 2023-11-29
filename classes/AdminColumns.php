<?php

declare(strict_types=1);

namespace AC;

use AC\Admin\MenuGroupFactory;
use AC\Admin\MenuGroupFactory\DefaultGroups;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Asset\Location\Absolute;
use AC\Asset\Script\Localize\Translation;
use AC\Controller\RestoreSettingsRequest;
use AC\Entity\Plugin;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\ListScreenRepository\Types;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\RequestHandler\Ajax\ListScreenDelete;
use AC\Storage\EncoderFactory;
use AC\Vendor\DI;
use AC\Vendor\DI\ContainerBuilder;

use function AC\Vendor\DI\autowire;

class AdminColumns
{

    public function __construct()
    {
        $container = $this->create_container();

        Container::set_container($container);

        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\PostFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\CommentFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\MediaFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\UserFactory::class));

        MenuGroupFactory\Aggregate::add(new DefaultGroups());

        ListKeysFactory\Aggregate::add($container->get(ListKeysFactory\BaseFactory::class));

        $page_handler = new PageRequestHandler();
        $page_handler->add('columns', $container->get(PageFactory\Columns::class))
                     ->add('settings', $container->get(PageFactory\Settings::class))
                     ->add('addons', $container->get(PageFactory\Addons::class))
                     ->add('help', $container->get(PageFactory\Help::class));

        PageRequestHandlers::add_handler($page_handler);

        $this->create_services($container)
             ->register();
    }

    private function create_services(DI\Container $container): Services
    {
        $services_fqn = [
            PluginActionLinks::class,
            Screen::class,
            Admin\Admin::class,
            Admin\Scripts::class,
            Admin\Notice\ReadOnlyListScreen::class,
            Admin\Notice\DatabaseMissing::class,
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
            Controller\RestoreSettingsRequest::class,
            Controller\TableListScreenSetter::class,
            Service\IntegrationColumns::class,
            Service\CommonAssets::class,
            Service\Colors::class,
        ];

        if ( ! defined('ACP_FILE')) {
            $services_fqn[] = Service\NoticeChecks::class;
            $services_fqn[] = PluginActionUpgrade::class;
            $services_fqn[] = Service\ColumnsMockup::class;
        }

        $services = new Services();

        foreach ($services_fqn as $service_fqn) {
            $services->add($container->get($service_fqn));
        }

        $services->add(
            new Service\Setup($container->get(SetupFactory\AdminColumns::class)->create(SetupFactory::SITE))
        );

        $request_ajax_handlers = new RequestAjaxHandlers();
        $request_ajax_handlers->add('ac-list-screen-delete', $container->get(ListScreenDelete::class));

        $services->add(
            new RequestAjaxParser($request_ajax_handlers)
        );

        if ($container->get(Plugin::class)->is_network_active()) {
            $services->add(
                new Service\Setup($container->get(SetupFactory\AdminColumns::class)->create(SetupFactory::NETWORK))
            );
        }

        return $services;
    }

    private function create_container(): DI\Container
    {
        $definitions = [
            'translations.global'                   => static function (Plugin $plugin): Translation {
                return new Translation(require $plugin->get_dir() . 'settings/translations/global.php');
            },
            Storage::class                          => static function (Database $database): Storage {
                $storage = new Storage();
                $storage->set_repositories([
                    Types::DATABASE => new ListScreenRepository\Storage\ListScreenRepository($database, true),
                ]);

                return $storage;
            },
            RestoreSettingsRequest::class           => static function (Storage $storage): RestoreSettingsRequest {
                return new RestoreSettingsRequest($storage->get_repository(Types::DATABASE));
            },
            Plugin::class                           => static function (): Plugin {
                return Plugin::create(AC_FILE, new Version(AC_VERSION));
            },
            TableScreenFactory::class               => autowire(TableScreenFactory\Aggregate::class),
            Absolute::class                         => static function (Plugin $plugin): Absolute {
                return new Absolute($plugin->get_url(), $plugin->get_dir());
            },
            SetupFactory\AdminColumns::class        => static function (
                Absolute $location,
                Plugin $plugin
            ): SetupFactory\AdminColumns {
                return new SetupFactory\AdminColumns('ac_version', $plugin->get_version(), $location);
            },
            ListKeysFactory::class                  => autowire(ListKeysFactory\Aggregate::class),
            Service\CommonAssets::class             => autowire()
                ->constructorParameter(1, DI\get('translations.global')),
            Admin\Colors\Shipped\ColorParser::class => autowire()
                ->constructorParameter(0, ABSPATH . 'wp-admin/css/common.css'),
            Admin\Colors\ColorReader::class         => autowire(Admin\Colors\ColorRepository::class),
            Admin\Admin::class                      => autowire()
                ->constructorParameter(0, DI\get(PageRequestHandlers::class)),
            Admin\MenuFactoryInterface::class       => autowire(Admin\MenuFactory::class)
                ->constructorParameter(0, admin_url('options-general.php')),
            Admin\PageFactory\Settings::class       => autowire()
                ->constructorParameter(2, defined('ACP_FILE')),
            Service\IntegrationColumns::class       => autowire()
                ->constructorParameter(1, defined('ACP_FILE')),
            EncoderFactory::class                   => static function (Plugin $plugin) {
                return new EncoderFactory\BaseEncoderFactory($plugin->get_version());
            },
        ];

        return (new ContainerBuilder())
            ->addDefinitions($definitions)
            ->build();
    }

    public function get_storage(): Storage
    {
        _deprecated_function(__METHOD__, '4.6.5', 'AC\Container::get_storage()');

        return Container::get_storage();
    }

    public function get_url(): string
    {
        _deprecated_function(__METHOD__, '4.6.5', 'ac_get_url()');

        return trailingslashit(Container::get_location()->get_url());
    }

    /**
     * @deprecated
     */
    public function install(): void
    {
    }

}
