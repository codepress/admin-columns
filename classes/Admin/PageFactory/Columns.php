<?php

namespace AC\Admin\PageFactory;

use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Admin\Section;
use AC\Asset\Location;
use AC\Controller\Middleware;
use AC\DefaultColumnsRepository;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\Table\TableScreensFactoryInterface;
use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use InvalidArgumentException;

class Columns implements PageFactoryInterface
{

    protected $storage;

    protected $location;

    protected $menu_factory;

    protected $list_screen_factory;

    protected $uninitialized_screens;

    private $menu_list_factory;

    private $table_screen_factory;

    private $preference;

    private $table_screens_factory;

    public function __construct(
        Storage $storage,
        Location\Absolute $location,
        MenuFactoryInterface $menu_factory,
        ListScreenFactory $list_screen_factory,
        TableScreenFactory $table_screen_factory,
        Admin\UninitializedScreens $uninitialized_screens,
        Admin\MenuListFactory $menu_list_factory,
        Preference\ListScreen $preference,
        TableScreensFactoryInterface $table_screens_factory
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->menu_factory = $menu_factory;
        $this->list_screen_factory = $list_screen_factory;
        $this->uninitialized_screens = $uninitialized_screens;
        $this->menu_list_factory = $menu_list_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->preference = $preference;
        $this->table_screens_factory = $table_screens_factory;
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
                $table_screen->get_key(),
                $this->preference,
                $this->list_screen_factory
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            throw new InvalidArgumentException('Invalid screen.');
        }

        $this->set_preference(
            $table_screen->get_key(),
            $list_screen->has_id() ? $list_screen->get_id() : null
        );

        return new Page\Columns(
            $this->location,
            new DefaultColumnsRepository($table_screen->get_key()),
            $this->uninitialized_screens->find_all_sites(),
            new Section\Partial\Menu($this->menu_list_factory),
            new Admin\View\Menu($this->menu_factory->create('columns')),
            $table_screen,
            $list_screen
        );
    }

    private function set_preference(ListKey $key, ListScreenId $id = null): void
    {
        $this->preference->set_last_visited_list_key((string)$key);

        if ($id) {
            $this->preference->set_list_id((string)$key, (string)$id);
        }
    }

}