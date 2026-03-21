<?php

declare(strict_types=1);

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\Banner\BannerContextResolver;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Admin\View;
use AC\AdminColumns;
use AC\ColumnGroups;
use AC\Integration\IntegrationRepository;
use AC\Promo\PromoRepository;
use AC\Request;
use AC\Storage\Repository\EditorFavorites;
use AC\Table\TableScreenRepository;
use AC\TableScreen;
use AC\Type\ListScreenId;
use InvalidArgumentException;

final class Columns implements PageFactoryInterface
{

    private bool $is_pro_active;

    private AdminColumns $plugin;

    private MenuFactoryInterface $menu_factory;

    private Admin\UninitializedScreens $uninitialized_screens;

    private Admin\MenuListFactory $menu_list_factory;

    private TableScreenRepository $table_screen_repository;

    private EditorFavorites $favorite_repository;

    private ColumnGroups $column_groups;

    private PromoRepository $promos;

    private IntegrationRepository $integration_repository;

    private View\MenuFactory $view_menu_factory;

    private BannerContextResolver $banner_context_resolver;

    public function __construct(
        bool $is_pro_active,
        AdminColumns $plugin,
        MenuFactoryInterface $menu_factory,
        Admin\UninitializedScreens $uninitialized_screens,
        Admin\MenuListFactory $menu_list_factory,
        TableScreenRepository $table_screen_repository,
        EditorFavorites $favorite_repository,
        ColumnGroups $column_groups,
        PromoRepository $promos,
        IntegrationRepository $integration_repository,
        View\MenuFactory $view_menu_factory,
        BannerContextResolver $banner_context_resolver
    ) {
        $this->is_pro_active = $is_pro_active;
        $this->plugin = $plugin;
        $this->menu_factory = $menu_factory;
        $this->uninitialized_screens = $uninitialized_screens;
        $this->menu_list_factory = $menu_list_factory;
        $this->table_screen_repository = $table_screen_repository;
        $this->favorite_repository = $favorite_repository;
        $this->column_groups = $column_groups;
        $this->promos = $promos;
        $this->integration_repository = $integration_repository;
        $this->view_menu_factory = $view_menu_factory;
        $this->banner_context_resolver = $banner_context_resolver;
    }

    public function create(): Page\Columns
    {
        $request = new Request();

        $request->add_middleware(
            new Request\Middleware\TableScreenAdmin(
                new Preference\EditorPreference(),
                $this->table_screen_repository->find_all_site()
            )
        );

        $table_screen = $request->get('table_screen');

        if ( ! $table_screen instanceof TableScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        $list_id = ListScreenId::is_valid_id($request->get('layout_id'))
            ? new ListScreenId($request->get('layout_id'))
            : null;

        return new Page\Columns(
            $this->plugin,
            $this->uninitialized_screens->find_all_site(),
            $this->view_menu_factory->create($this->menu_factory, 'columns'),
            $table_screen,
            $this->menu_list_factory->create($this->table_screen_repository->find_all_site()),
            $this->favorite_repository,
            $this->table_screen_repository,
            $this->column_groups,
            $this->promos,
            $this->integration_repository,
            $this->is_pro_active,
            $this->banner_context_resolver,
            $list_id
        );
    }

}