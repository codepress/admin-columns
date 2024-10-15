<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Asset\Location;
use AC\Request;
use AC\Storage\Repository\EditorFavorites;
use AC\Table\TableScreenRepository;
use AC\TableScreen;
use AC\Type\ListScreenId;
use InvalidArgumentException;

class Columns implements PageFactoryInterface
{

    protected $location;

    protected $menu_factory;

    protected $uninitialized_screens;

    private $menu_list_factory;

    private $table_screen_repository;

    private $favorite_repository;

    public function __construct(
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        Admin\UninitializedScreens $uninitialized_screens,
        Admin\MenuListFactory $menu_list_factory,
        TableScreenRepository $table_screen_repository,
        EditorFavorites $favorite_repository
    ) {
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->uninitialized_screens = $uninitialized_screens;
        $this->menu_list_factory = $menu_list_factory;
        $this->table_screen_repository = $table_screen_repository;
        $this->favorite_repository = $favorite_repository;
    }

    public function create(): Page\Columns
    {
        $request = new Request();

        $request->add_middleware(
            new Request\Middleware\TableScreenAdmin(
                new Preference\ListScreen(),
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
            $this->location,
            $this->uninitialized_screens->find_all_site(),
            new Admin\View\Menu($this->menu_factory->create('columns')),
            $table_screen,
            $this->menu_list_factory->create($this->table_screen_repository->find_all_site()),
            $this->favorite_repository,
            $this->table_screen_repository,
            $list_id
        );
    }

}