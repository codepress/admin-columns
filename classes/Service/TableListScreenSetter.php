<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Storage\Repository\DefaultColumnsRepository;
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

    private $default_columns_repository;

    public function __construct(
        Storage $storage,
        Absolute $location,
        TableScreenFactory $table_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory,
        DefaultColumnsRepository $default_columns_repository
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->default_columns_repository = $default_columns_repository;
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

        do_action('ac/table/screen', $table_screen);

        $request = new Request();

        $request->add_middleware(
            new Request\Middleware\ListScreenTable(
                $this->storage,
                $table_screen->get_key(),
                $this->preference
            )
        );

        $list_screen = $request->get('list_screen');

        if ($list_screen instanceof ListScreen) {
            $this->preference->save(
                $table_screen->get_key(),
                $list_screen->get_id()
            );

            do_action('ac/table/list_screen', $list_screen, $table_screen);
        }

        $table = new Table\Screen(
            $this->location,
            $table_screen,
            new ColumnSize\ListStorage($this->storage),
            new ColumnSize\UserStorage(),
            $this->primary_column_factory,
            $this->default_columns_repository,
            $list_screen
        );
        $table->register();

        do_action('ac/table', $table);
    }

}