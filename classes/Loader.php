<?php

declare(strict_types=1);

namespace AC;

use AC\Admin\MenuGroupFactory;
use AC\Admin\MenuGroupFactory\DefaultGroups;
use AC\Admin\PageFactory;
use AC\Admin\PageRequestHandler;
use AC\Admin\PageRequestHandlers;
use AC\Plugin\SetupFactory;
use AC\RequestHandler\Ajax;
use AC\Setting\ContextFactory;
use AC\Table\ManageHeading;
use AC\Table\ManageValue\ListScreenServiceFactory;
use AC\Table\SaveHeading;
use AC\Value\Extended\MediaPreview;
use AC\Value\Extended\Posts;
use AC\Value\ExtendedValueRegistry;
use AC\Vendor\Psr\Container\ContainerInterface;

class Loader
{

    public function __construct(ContainerInterface $container)
    {
        Container::set_container($container);

        $this->load($container);
    }

    protected function load(ContainerInterface $container): void
    {
        // Factories
        $factories = [
            TableScreenFactory\CommentFactory::class,
            TableScreenFactory\MediaFactory::class,
            TableScreenFactory\PostFactory::class,
            TableScreenFactory\UserFactory::class,
        ];

        foreach ($factories as $factory) {
            TableScreenFactory\Aggregate::add($container->get($factory));
        }

        $factories = [
            ColumnFactories\OriginalFactory::class,
            ColumnFactories\PostFactory::class,
            ColumnFactories\CommentFactory::class,
            ColumnFactories\MediaFactory::class,
            ColumnFactories\UserFactory::class,
            ColumnFactories\ThirdPartyFactory::class,
        ];

        if ( ! defined('ACP_FILE')) {
            $factories[] = ColumnFactories\IntegrationFactory::class;
        }

        foreach ($factories as $factory) {
            ColumnFactories\Aggregate::add($container->get($factory));
        }

        ExtendedValueRegistry::add($container->get(MediaPreview::class));
        ExtendedValueRegistry::add($container->get(Posts::class));

        MenuGroupFactory\Aggregate::add($container->get(DefaultGroups::class));
        TableIdsFactory\Aggregate::add($container->get(TableIdsFactory\BaseFactory::class));
        TableScreen\TableRowsFactory\Aggregate::add(new TableScreen\TableRowsFactory\BaseFactory());

        // Page handlers
        $page_handler = new PageRequestHandler();
        $page_handler->add('columns', $container->get(PageFactory\Columns::class))
                     ->add('settings', $container->get(PageFactory\Settings::class))
                     ->add('addons', $container->get(PageFactory\Addons::class))
                     ->add('help', $container->get(PageFactory\Help::class));

        PageRequestHandlers::add_handler($page_handler);

        // Value handlers
        foreach (
            [
                $container->get(TableScreen\ManageValue\PostServiceFactory::class),
                $container->get(TableScreen\ManageValue\UserServiceFactory::class),
                $container->get(TableScreen\ManageValue\MediaServiceFactory::class),
                $container->get(TableScreen\ManageValue\CommentServiceFactory::class),
            ] as $factory
        ) {
            Service\ManageValue::add(
                $container->make(
                    ListScreenServiceFactory::class,
                    ['factory' => $factory]
                )
            );
        }

        Service\ManageHeadings::add($container->get(ManageHeading\WpListTableFactory::class));
        Service\SaveHeadings::add($container->get(SaveHeading\WpListTableFactory::class));

        $aggregateContextFactory = $container->get(ContextFactory\Aggregate::class);
        if ($aggregateContextFactory instanceof ContextFactory\Aggregate) {
            $aggregateContextFactory->add($container->get(ContextFactory\CustomField::class));
        }

        // Services
        $services_fqn = [
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
        ];

        if ( ! defined('ACP_FILE')) {
            $services_fqn[] = Service\NoticeChecks::class;
            $services_fqn[] = PluginActionUpgrade::class;
        }

        foreach ($services_fqn as $service_fqn) {
            $service = $container->get($service_fqn);
            $service->register();
        }

        $setup_factory = $container->get(SetupFactory\AdminColumns::class);
        $setup = new Service\Setup($setup_factory->create(SetupFactory::SITE));
        $setup->register();

        if ($container->get(AdminColumns::class)->is_network_active()) {
            $setup = new Service\Setup($setup_factory->create(SetupFactory::NETWORK));
            $setup->register();
        }

        $handlers = [
            'ac-list-screen-settings'         => Ajax\ListScreenSettings::class,
            // TODO Tobias is this used anywhere? Surfaced because of the double hook 'ac-list-screen-delete'
            'ac-list-screen-delete'           => Ajax\ListScreenDelete::class,
            'ac-list-screen-save'             => Ajax\ListScreenSave::class,
            'ac-list-screen-add-column'       => Ajax\ListScreenAddColumn::class,
            'ac-number-format'                => Ajax\NumberFormat::class,
            'ac-list-screen-default-columns'  => Ajax\ListScreenDefaultColumns::class,
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
        ];

        $request_ajax_handlers = new RequestAjaxHandlers();

        foreach ($handlers as $key => $handler) {
            $request_ajax_handlers->add($key, $container->get($handler));
        }

        $request_ajax_parser = new RequestAjaxParser($request_ajax_handlers);
        $request_ajax_parser->register();
    }

}