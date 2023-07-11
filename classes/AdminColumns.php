<?php

declare(strict_types=1);

namespace AC;

use AC\Admin;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Asset\Location\Absolute;
use AC\Asset\Script\Localize\Translation;
use AC\Controller;
use AC\Controller\RestoreSettingsRequest;
use AC\Entity;
use AC\ListScreenFactory\Aggregate;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\Service;
use AC\Table;
use AC\Table\ListKeysFactoryInterface;
use AC\ThirdParty;
use AC\Vendor\DI;
use AC\Vendor\DI\ContainerBuilder;

use function AC\Vendor\DI\autowire;

class AdminColumns
{

    public function __construct()
    {
        $container = $this->create_container();

        Container::set_container($container);

        ListScreenFactory\Aggregate::add($container->get(ListScreenFactory\UserFactory::class));
        ListScreenFactory\Aggregate::add($container->get(ListScreenFactory\CommentFactory::class));
        ListScreenFactory\Aggregate::add($container->get(ListScreenFactory\PostFactory::class));
        ListScreenFactory\Aggregate::add($container->get(ListScreenFactory\MediaFactory::class));

        $page_handler = new PageRequestHandler();
        $page_handler->add('columns', $container->get(Admin\PageFactory\Columns::class))
                     ->add('settings', $container->get(Admin\PageFactory\Settings::class))
                     ->add('addons', $container->get(Admin\PageFactory\Addons::class))
                     ->add('help', $container->get(Admin\PageFactory\Help::class));

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

        if ( ! defined('ACP_FILE')) {
            $services_fqn[] = Service\NoticeChecks::class;
            $services_fqn[] = PluginActionUpgrade::class;
            $services_fqn[] = Service\ColumnsMockup::class;
        }

        $services = new Services([
            new Service\Setup($container->get(SetupFactory\AdminColumns::class)->create(SetupFactory::SITE)),
        ]);

        foreach ($services_fqn as $service_fqn) {
            $services->add($container->get($service_fqn));
        }

        $plugin = $container->get(Entity\Plugin::class);

        if ($plugin->is_network_active()) {
            $services->add(
                new Service\Setup($container->get(SetupFactory\AdminColumns::class)->create(SetupFactory::NETWORK))
            );
        }

        return $services;
    }

    private function create_container(): DI\Container
    {
        $plugin = new Entity\Plugin(AC_FILE, new Version(AC_VERSION));

        $definitions = [
            'translations.global'                   => function () use ($plugin): Translation {
                return new Translation(require $plugin->get_dir() . '/settings/translations/global.php');
            },
            Database::class                         => autowire()
                ->constructorParameter(0, new ListScreenFactory\Aggregate()),
            Storage::class                          => static function (Database $database): Storage {
                $storage = new Storage();
                $storage->set_repositories([
                    'acp-database' => new ListScreenRepository\Storage\ListScreenRepository($database, true),
                ]);

                return $storage;
            },
            RestoreSettingsRequest::class           => static function (Storage $storage): RestoreSettingsRequest {
                return new RestoreSettingsRequest($storage->get_repository('acp-database'));
            },
            Entity\Plugin::class                    => autowire()
                ->constructorParameter(0, AC_FILE)
                ->constructorParameter(1, new Version(AC_VERSION)),
            ListScreenFactory::class                => autowire(Aggregate::class),
            Absolute::class                         => autowire()
                ->constructorParameter(0, $plugin->get_url())
                ->constructorParameter(1, $plugin->get_dir()),
            ListKeysFactoryInterface::class         => autowire(Table\ListKeysFactory::class),
            Service\CommonAssets::class             => autowire()
                ->constructorParameter(1, DI\get('translations.global')),
            Admin\Colors\Shipped\ColorParser::class => autowire()
                ->constructorParameter(0, ABSPATH . 'wp-admin/css/common.css'),
            Admin\Colors\ColorReader::class         => autowire(Admin\Colors\ColorRepository::class),
            Admin\Admin::class                      => autowire()
                ->constructorParameter(0, DI\get(PageRequestHandlers::class)),
            Admin\MenuFactoryInterface::class       => autowire(Admin\MenuFactory::class)
                ->constructorParameter(0, admin_url('options-general.php')),
            Admin\MenuListFactory::class            => autowire(Admin\MenuListFactory\MenuFactory::class),
            Admin\PageFactory\Settings::class       => autowire()
                ->constructorParameter(2, defined('ACP_FILE')),
            SetupFactory\AdminColumns::class        => autowire()
                ->constructorParameter(0, 'ac_version')
                ->constructorParameter(1, $plugin->get_version()),
            Service\Setup::class                    => autowire()
                ->constructorParameter(0, DI\get(SetupFactory\AdminColumns::class)),
        ];

        return (new ContainerBuilder())
            ->addDefinitions($definitions)
            ->build();
    }

    public function get_url(): string
    {
        return trailingslashit(Container::get_url());
    }

    public function get_storage(): Storage
    {
        return Container::get_storage();
    }

    /**
     * @deprecated
     */
    public function install(): void
    {
    }

}
