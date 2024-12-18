<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
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

    private Table\InlineStyle\ColumnSize $column_size;

    public function __construct(
        Storage $storage,
        Absolute $location,
        TableScreenFactory $table_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory,
        Table\InlineStyle\ColumnSize $column_size
    ) {
        $this->storage = $storage;
        $this->location = $location;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->column_size = $column_size;
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

            $list = new Table\Service\ListScreen(
                $list_screen,
                $this->primary_column_factory,
                $this->column_size
            );
            $list->register();

            do_action('ac/table/list_screen', $list_screen, $table_screen);
        }

        $table = new Table\Screen(
            $this->location,
            $table_screen,
            $list_screen
        );

        do_action('ac/table', $table);

        $table->register();
    }

}