<?php

declare(strict_types=1);

namespace AC;

use AC\Admin\MenuGroupFactory;
use AC\Admin\MenuGroupFactory\DefaultGroups;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Asset\Location\Absolute;
use AC\Asset\Script\GlobalTranslationFactory;
use AC\Asset\Script\Localize\Translation;
use AC\Entity\Plugin;
use AC\ListScreenRepository\Database;
use AC\ListScreenRepository\Storage;
use AC\ListScreenRepository\Types;
use AC\Plugin\SetupFactory;
use AC\Plugin\Version;
use AC\RequestHandler\Ajax;
use AC\RequestHandler\Ajax\RestoreSettingsRequest;
use AC\Storage\EncoderFactory;
use AC\Table\ManageValue\ListScreenServiceFactory;
use AC\Value\Extended\MediaPreview;
use AC\Value\ExtendedValueRegistry;
use AC\Vendor\DI;
use AC\Vendor\DI\ContainerBuilder;

use function AC\Vendor\DI\autowire;

class AdminColumns
{

    public function __construct()
    {
        $container = $this->create_container();

        Container::set_container($container);

        $this->define_factories($container);
        $this->create_services($container)
             ->register();
    }

    private function define_factories(DI\Container $container): void
    {
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\CommentFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\MediaFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\PostFactory::class));
        TableScreenFactory\Aggregate::add($container->get(TableScreenFactory\UserFactory::class));

        ColumnFactories\Aggregate::add($container->get(ColumnFactories\OriginalFactory::class));
        ColumnFactories\Aggregate::add($container->get(ColumnFactories\PostFactory::class));
        ColumnFactories\Aggregate::add($container->get(ColumnFactories\CommentFactory::class));
        ColumnFactories\Aggregate::add($container->get(ColumnFactories\MediaFactory::class));
        ColumnFactories\Aggregate::add($container->get(ColumnFactories\UserFactory::class));
        ColumnFactories\Aggregate::add($container->get(ColumnFactories\ThirdPartyFactory::class));

        foreach (
            [
                $container->get(TableScreen\ManageValue\PostFactory::class),
                $container->get(TableScreen\ManageValue\UserFactory::class),
                $container->get(TableScreen\ManageValue\MediaFactory::class),
                $container->get(TableScreen\ManageValue\CommentFactory::class),
            ] as $factory
        ) {
            Table\ManageValue\AggregateServiceFactory::add(new ListScreenServiceFactory($factory));
        }

        if ( ! defined('ACP_FILE')) {
            ColumnFactories\Aggregate::add($container->get(ColumnFactories\IntegrationFactory::class));
        }

        ExtendedValueRegistry::add($container->get(MediaPreview::class));

        MenuGroupFactory\Aggregate::add($container->get(DefaultGroups::class));
        ListKeysFactory\Aggregate::add($container->get(ListKeysFactory\BaseFactory::class));
        TableScreen\TableRowsFactory\Aggregate::add(new TableScreen\TableRowsFactory\BaseFactory());

        $page_handler = new PageRequestHandler();
        $page_handler->add('columns', $container->get(PageFactory\Columns::class))
                     ->add('settings', $container->get(PageFactory\Settings::class))
                     ->add('addons', $container->get(PageFactory\Addons::class))
                     ->add('help', $container->get(PageFactory\Help::class));

        PageRequestHandlers::add_handler($page_handler);
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
            ThirdParty\AdvancedCustomFields::class,
            ThirdParty\NinjaForms::class,
            ThirdParty\MediaLibraryAssistant\MediaLibraryAssistant::class,
            ThirdParty\WooCommerce::class,
            ThirdParty\WPML::class,
            Service\DefaultColumns::class,
            Screen\QuickEdit::class,
            Capabilities\Manage::class,
            Service\TableListScreenSetter::class,
            Service\CommonAssets::class,
            Service\Colors::class,
            Service\TableRows::class,
            Service\ManageValue::class,
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
        $request_ajax_handlers->add('ac-list-screen-settings', $container->get(Ajax\ListScreenSettings::class));
        $request_ajax_handlers->add('ac-list-screen-delete', $container->get(Ajax\ListScreenDelete::class));
        $request_ajax_handlers->add('ac-list-screen-save', $container->get(Ajax\ListScreenSave::class));
        $request_ajax_handlers->add('ac-list-screen-add-column', $container->get(Ajax\ListScreenAddColumn::class));
        $request_ajax_handlers->add('ac-number-format', $container->get(Ajax\NumberFormat::class));
        $request_ajax_handlers->add(
            'ac-list-screen-default-columns',
            $container->get(Ajax\ListScreenDefaultColumns::class)
        );
        $request_ajax_handlers->add(
            'ac-list-screen-select-column',
            $container->get(Ajax\ListScreenSelectColumn::class)
        );
        $request_ajax_handlers->add('ac-editor-menu-status', $container->get(Ajax\EditorMenuStatus::class));
        $request_ajax_handlers->add('ac-editor-menu-favorites', $container->get(Ajax\EditorMenuFavorites::class));
        $request_ajax_handlers->add('ac-custom-field-keys', $container->get(Ajax\CustomFieldKeys::class));
        $request_ajax_handlers->add('ac-admin-screen-options', $container->get(Ajax\ScreenOptions::class));
        $request_ajax_handlers->add('ac-extended-value', $container->get(Ajax\ExtendedValue::class));
        $request_ajax_handlers->add('ac-admin-general-options', $container->get(Ajax\AdminGeneralOptions::class));
        $request_ajax_handlers->add('ac-restore-settings', $container->get(Ajax\RestoreSettingsRequest::class));

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
            GlobalTranslationFactory::class         => autowire()
                ->constructorParameter(1, DI\get('translations.global')),
            ListKeysFactory::class                  => autowire(ListKeysFactory\Aggregate::class),
            Admin\Colors\Shipped\ColorParser::class => autowire()
                ->constructorParameter(0, ABSPATH . 'wp-admin/css/common.css'),
            Admin\Colors\ColorReader::class         => autowire(Admin\Colors\ColorRepository::class),
            Admin\Admin::class                      => autowire()
                ->constructorParameter(0, DI\get(PageRequestHandlers::class)),
            Admin\MenuFactoryInterface::class       => autowire(Admin\MenuFactory::class)
                ->constructorParameter(0, admin_url('options-general.php')),
            Admin\PageFactory\Settings::class       => autowire()
                ->constructorParameter(2, defined('ACP_FILE')),
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
