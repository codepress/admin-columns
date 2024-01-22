<?php

namespace AC\Controller;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Table;
use AC\TableScreenFactory;
use WP_Screen;

class TableListScreenSetter implements Registerable
{

    private $storage;

    private $location;

    private $preference;

    private $primary_column_factory;

    private $table_screen_factory;

    public function __construct(
        Storage $storage,
        Absolute $location,
        TableScreenFactory $table_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function register(): void
    {
        add_action('current_screen', [$this, 'handle']);
    }

    public function handle(WP_Screen $wp_screen): void
    {
        if ( ! $this->table_screen_factory->can_create_from_wp_screen($wp_screen)) {
            return;
        }

        $table_screen = $this->table_screen_factory->create_from_wp_screen($wp_screen);

        $request = new Request();

        $request->add_middleware(
            new Middleware\ListScreenTable(
                $this->storage,
                $table_screen->get_key(),
                $this->preference
            )
        );

        $list_screen = $request->get('list_screen');

        if ($list_screen) {
            $this->preference->save(
                $list_screen->get_key(),
                (string)$list_screen->get_id()
            );
        }

        $table_screen = new Table\Screen(
            $this->location,
            $table_screen,
            new ColumnSize\ListStorage($this->storage),
            new ColumnSize\UserStorage(new ColumnSize\UserPreference()),
            $this->primary_column_factory,
            $list_screen
        );
        $table_screen->register();

        do_action('ac/table', $table_screen);
    }

}