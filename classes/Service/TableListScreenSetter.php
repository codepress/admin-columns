<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Table;
use AC\TableScreenFactory;
use WP_Screen;

class TableListScreenSetter implements Registerable
{

    private Storage $storage;

    private Absolute $location;

    private Table\LayoutPreference $preference;

    private Table\PrimaryColumnFactory $primary_column_factory;

    private TableScreenFactory $table_screen_factory;

    private ColumnSize\ListStorage $size_storage;

    private ColumnSize\UserStorage $size_user_storage;

    public function __construct(
        Storage $storage,
        AdminColumns $plugin,
        TableScreenFactory $table_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory,
        ColumnSize\ListStorage $size_storage,
        ColumnSize\UserStorage $size_user_storage
    ) {
        $this->storage = $storage;
        $this->location = $plugin->get_location();
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->size_storage = $size_storage;
        $this->size_user_storage = $size_user_storage;
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

        do_action('ac/table/screen', $table_screen, $list_screen);

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
            $this->size_storage,
            $this->size_user_storage,
            $this->primary_column_factory,
            $list_screen
        );

        $table->register();

        do_action('ac/table', $table);
    }

}