<?php

declare(strict_types=1);

namespace AC;

use AC;
use AC\Admin\MenuGroupFactory;
use AC\Admin\MenuGroupFactory\DefaultGroups;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\ListScreenRepository\Storage;
use AC\Plugin\SetupFactory;
use AC\RequestHandler\Ajax;
use AC\Service\View;
use AC\Table\ManageHeading;
use AC\Table\ManageValue\ListScreenServiceFactory;
use AC\Table\SaveHeading;
use AC\Value\Extended\MediaPreview;
use AC\Value\Extended\Posts;
use AC\Value\ExtendedValueRegistry;

class Loader
{

    private bool $is_pro_active;

    public function __construct(AC\DI\Container $container, bool $is_pro_active = false)
    {
        $this->is_pro_active = $is_pro_active;

        Container::set_container($container);

        $this->load($container);
    }

    protected function load(AC\DI\Container $container): void
    {
        $this->register_factories($container);
        $this->register_page_handlers($container);
        $this->register_services($container);

        // Setup Registry for API
        Registry::set(Storage::class, static function () use ($container) {
            return $container->get(Storage::class);
        });

        $plugin = $container->get(AdminColumns::class);

        /**
         * @param AC\DI\Container $container A PSR-11 dependency injection container.
         * @param AdminColumns    $plugin    The plugin instance.
         */
        do_action('ac/init', $container, $plugin);
    }

    private function register_factories(AC\DI\Container $container): void
    {
        foreach ($this->get_table_screen_factory_classes() as $class) {
            TableScreenFactory\Aggregate::add($container->get($class));
        }

        foreach ($this->get_column_factory_classes() as $class) {
            ColumnFactories\Aggregate::add($container->get($class));
        }

        ExtendedValueRegistry::add($container->get(MediaPreview::class));
        ExtendedValueRegistry::add($container->get(Posts::class));

        MenuGroupFactory\Aggregate::add($container->get(DefaultGroups::class));

        foreach ($this->get_menu_group_factory_classes() as $class) {
            MenuGroupFactory\Aggregate::add(new $class(), 5);
        }
        TableIdsFactory\Aggregate::add($container->get(TableIdsFactory\BaseFactory::class));
        TableScreen\TableRowsFactory\Aggregate::add(new TableScreen\TableRowsFactory\BaseFactory());

        foreach ($this->get_manage_value_factory_classes() as $class) {
            Service\ManageValue::add(
                $container->make(ListScreenServiceFactory::class, ['factory' => $container->get($class)])
            );
        }

        Service\ManageHeadings::add($container->get(ManageHeading\WpListTableFactory::class));
        Service\SaveHeadings::add($container->get(SaveHeading\WpListTableFactory::class));
    }

    private function get_table_screen_factory_classes(): array
    {
        return [
            TableScreenFactory\CommentFactory::class,
            TableScreenFactory\MediaFactory::class,
            TableScreenFactory\PostFactory::class,
            TableScreenFactory\UserFactory::class,
        ];
    }

    private function get_column_factory_classes(): array
    {
        $classes = [
            ColumnFactories\OriginalFactory::class,
            ColumnFactories\PostFactory::class,
            ColumnFactories\CommentFactory::class,
            ColumnFactories\MediaFactory::class,
            ColumnFactories\UserFactory::class,
        ];

        if ( ! $this->is_pro_active) {
            $classes[] = ColumnFactories\ThirdPartyFactory::class;
            $classes[] = ColumnFactories\IntegrationFactory::class;
        }

        return $classes;
    }

    private function get_manage_value_factory_classes(): array
    {
        return [
            TableScreen\ManageValue\PostServiceFactory::class,
            TableScreen\ManageValue\UserServiceFactory::class,
            TableScreen\ManageValue\MediaServiceFactory::class,
            TableScreen\ManageValue\CommentServiceFactory::class,
        ];
    }

    private function get_menu_group_factory_classes(): array
    {
        return [
            MenuGroupFactory\WooCommerceGroups::class,
            MenuGroupFactory\BuddyPressGroups::class,
            MenuGroupFactory\EventsCalendarGroups::class,
            MenuGroupFactory\GravityFormsGroups::class,
            MenuGroupFactory\JetEngineGroups::class,
            MenuGroupFactory\MediaLibraryAssistantGroups::class,
            MenuGroupFactory\MetaBoxGroups::class,
            MenuGroupFactory\BeaverBuilderGroups::class,
        ];
    }

    private function register_page_handlers(AC\DI\Container $container): void
    {
        $page_handler = new PageRequestHandler();
        $page_handler
            ->add('columns', $container->get(PageFactory\Columns::class))
            ->add('settings', $container->get(PageFactory\Settings::class))
            ->add('addons', $container->get(PageFactory\Addons::class))
            ->add('help', $container->get(PageFactory\Help::class));

        PageRequestHandlers::add_handler($page_handler);
    }

    private function get_ajax_handler_classes(): array
    {
        return [
            'ac-list-screen-settings'         => Ajax\ListScreenSettings::class,
            'ac-list-screen-save'             => Ajax\ListScreenSave::class,
            'ac-list-screen-add-column'       => Ajax\ListScreenAddColumn::class,
            'ac-number-format'                => Ajax\NumberFormat::class,
            'ac-list-screen-default-columns'  => Ajax\ListScreenOriginalColumns::class,
            'ac-list-screen-select-column'    => Ajax\ListScreenSelectColumn::class,
            'ac-editor-menu-status'           => Ajax\EditorMenuStatus::class,
            'ac-editor-menu-favorites'        => Ajax\EditorMenuFavorites::class,
            'ac-custom-field-keys'            => Ajax\CustomFieldKeys::class,
            'ac-get-network-post-statuses'    => Ajax\NetworkPostStati::class,
            'ac-admin-screen-options'         => Ajax\ScreenOptions::class,
            'ac-extended-value'               => Ajax\ExtendedValue::class,
            'ac-persist-admin-general-option' => Ajax\AdminGeneralOptionsPersist::class,
            'ac-get-admin-general-option'     => Ajax\AdminGeneralOptionsGet::class,
            'ac-restore-settings'             => Ajax\RestoreSettingsRequest::class,
            'ac-integration-toggle'           => Ajax\IntegrationToggle::class,
            'ac-integrations'                 => Ajax\Integrations::class,
            'ac-list-screen-delete'           => Ajax\ListScreenDelete::class,
            'ac-acf-add-column'               => AC\Acf\RequestHandler\FieldSettingsAddColumn::class,
        ];
    }

    private function get_service_classes(): array
    {
        $classes = [
            PluginActionLinks::class,
            Screen::class,
            Admin\Admin::class,
            Admin\Scripts::class,
            Admin\Notice\DatabaseMissing::class,
            ThirdParty\AdvancedCustomFields::class,
            ThirdParty\NinjaForms::class,
            ThirdParty\MediaLibraryAssistant\MediaLibraryAssistant::class,
            ThirdParty\WooCommerce::class,
            ThirdParty\WPML::class,
            Capabilities\Manage::class,
            Service\QuickEdit::class,
            Service\CurrentTable::class,
            Service\CommonAssets::class,
            Service\Colors::class,
            Service\TableRows::class,
            Service\ManageValue::class,
            Service\ManageHeadings::class,
            Service\SaveHeadings::class,
            Service\AdminBarEditColumns::class,
            Service\PluginUpdate::class,
            Service\Tooltips::class,
            AC\Acf\Service\DateSaveFormat::class,
        ];

        if ( ! $this->is_pro_active) {
            $classes[] = Service\PromoChecks::class;
            $classes[] = Service\NoticeChecks::class;
            $classes[] = PluginActionUpgrade::class;
            $classes[] = AC\Acf\Service\FieldSettings::class;
        }

        return $classes;
    }

    private function register_services(AC\DI\Container $container): void
    {
        $services = [];

        foreach ($this->get_service_classes() as $class) {
            $services[] = $container->get($class);
        }

        $plugin = $container->get(AdminColumns::class);
        $setup_factory = $container->get(SetupFactory\AdminColumns::class);

        $services[] = new View($plugin->get_location());
        $services[] = new Service\Setup($setup_factory->create(SetupFactory::SITE));

        if ($plugin->is_network_active()) {
            $services[] = new Service\Setup($setup_factory->create(SetupFactory::NETWORK));
        }

        $request_ajax_handlers = new RequestAjaxHandlers();

        foreach ($this->get_ajax_handler_classes() as $key => $class) {
            $request_ajax_handlers->add($key, $container->get($class));
        }

        $services[] = new RequestAjaxParser($request_ajax_handlers);

        foreach ($services as $service) {
            $service->register();
        }
    }

}
