<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Asset\Location;
use AC\ColumnFactory;
use AC\ColumnTypesFactory;
use AC\Controller\Middleware;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\TableScreen;
use InvalidArgumentException;

class Columns implements PageFactoryInterface
{

    protected $storage;

    protected $location;

    protected $menu_factory;

    protected $uninitialized_screens;

    private $menu_list_factory;

    private $preference;

    private $column_types_factory;

    private $column_factory;

    private $table_screen_repository;

    public function __construct(
        Storage $storage,
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        Admin\UninitializedScreens $uninitialized_screens,
        Admin\MenuListFactory $menu_list_factory,
        Preference\ListScreen $preference,
        ColumnTypesFactory\Aggregate $column_types_factory,
        ColumnFactory $column_factory,
        AC\Table\TableScreenRepository $table_screen_repository
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->uninitialized_screens = $uninitialized_screens;
        $this->menu_list_factory = $menu_list_factory;
        $this->preference = $preference;
        $this->column_types_factory = $column_types_factory;
        $this->column_factory = $column_factory;
        $this->table_screen_repository = $table_screen_repository;
    }

    public function create(): Page\Columns
    {
        $request = new Request();

        $request->add_middleware(
            new Middleware\TableScreenAdmin(
                new Preference\ListScreen(),
                $this->table_screen_repository->find_all_site()
            )
        );

        $table_screen = $request->get('table_screen');

        if ( ! $table_screen instanceof TableScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        // TODO
        //        $request->add_middleware(
        //            new Middleware\ListScreenAdmin(
        //                $this->storage,
        //                $table_screen,
        //                $this->preference,
        //                $this->column_types_factory,
        //                $this->column_factory
        //            )
        //        );

        //        $list_screen = $request->get('list_screen');
        //
        //        if ( ! $list_screen instanceof ListScreen) {
        //            throw new InvalidArgumentException('Invalid screen.');
        //        }

        $list_id = AC\Type\ListScreenId::is_valid_id($request->get('layout'))
            ? new AC\Type\ListScreenId($request->get('layout'))
            : null;

        return new Page\Columns(
            $this->location,
            $this->uninitialized_screens->find_all_site(),
            new AC\Admin\Section\Partial\Menu(
                $this->menu_list_factory->create($this->table_screen_repository->find_all_site())
            ),
            new Admin\View\Menu($this->menu_factory->create('columns')),
            $table_screen,
            $this->column_types_factory,
            $list_id
        );
    }

}