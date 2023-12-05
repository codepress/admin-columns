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
use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\Table\TableScreenCollection;
use AC\Table\TableScreensFactory;
use AC\TableScreen;
use AC\TableScreenFactory;
use InvalidArgumentException;

class Columns implements PageFactoryInterface
{

    protected $storage;

    protected $location;

    protected $menu_factory;

    protected $uninitialized_screens;

    private $menu_list_factory;

    private $table_screen_factory;

    private $preference;

    private $table_screens_factory;

    private $column_types_factory;

    private $column_factory;

    public function __construct(
        Storage $storage,
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        TableScreenFactory $table_screen_factory,
        Admin\UninitializedScreens $uninitialized_screens,
        Admin\MenuListFactory $menu_list_factory,
        Preference\ListScreen $preference,
        TableScreensFactory $table_screens_factory,
        ColumnTypesFactory $column_types_factory,
        ColumnFactory $column_factory
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->uninitialized_screens = $uninitialized_screens;
        $this->menu_list_factory = $menu_list_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->preference = $preference;
        $this->table_screens_factory = $table_screens_factory;
        $this->column_types_factory = $column_types_factory;
        $this->column_factory = $column_factory;
    }

    public function create()
    {
        $request = new Request();

        $request->add_middleware(
            new Middleware\TableScreenAdmin(
                new Preference\ListScreen(),
                $this->table_screen_factory,
                $this->table_screens_factory
            )
        );

        $table_screen = $request->get('table_screen');

        if ( ! $table_screen instanceof TableScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        $request->add_middleware(
            new Middleware\ListScreenAdmin(
                $this->storage,
                $table_screen,
                $this->preference,
                $this->column_types_factory,
                $this->column_factory
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        return new Page\Columns(
            $this->location,
            new DefaultColumnsRepository($table_screen->get_key()),
            $this->uninitialized_screens->find_all_sites(),
            new AC\Admin\Section\Partial\Menu($this->menu_list_factory->create($this->get_table_sceens())),
            new Admin\View\Menu($this->menu_factory->create('columns')),
            $table_screen,
            $list_screen,
            $this->storage,
            $this->column_types_factory
        );
    }

    private function get_table_sceens(): TableScreenCollection
    {
        return new TableScreenCollection(
            array_filter(iterator_to_array($this->table_screens_factory->create()), [$this, 'is_site'])
        );
    }

    public function is_site(TableScreen $table_screen): bool
    {
        return ! $table_screen->is_network();
    }

}