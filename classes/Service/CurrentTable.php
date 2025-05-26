<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Table;
use AC\TableScreenFactory;
use WP_Screen;

class CurrentTable implements Registerable
{

    private Storage $storage;

    private Table\LayoutPreference $preference;

    private Table\PrimaryColumnFactory $primary_column_factory;

    private TableScreenFactory $table_screen_factory;

    private Table\InlineStyle\ColumnSize $column_size;

    private AdminColumns $plugin;

    public function __construct(
        Storage $storage,
        AdminColumns $plugin,
        TableScreenFactory $table_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory,
        Table\InlineStyle\ColumnSize $column_size
    ) {
        $this->storage = $storage;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->table_screen_factory = $table_screen_factory;
        $this->column_size = $column_size;
        $this->plugin = $plugin;
    }

    public function register(): void
    {
        add_action('current_screen', [$this, 'handle']);
    }

    public function handle(WP_Screen $wp_screen): void
    {
        $user = wp_get_current_user();

        if ( ! $user) {
            return;
        }

        if ( ! $this->table_screen_factory->can_create_from_wp_screen($wp_screen)) {
            return;
        }

        $table_screen = $this->table_screen_factory->create_from_wp_screen($wp_screen);

        $request = new Request();

        $request->add_middleware(
            new Request\Middleware\ListScreenTable(
                $this->storage,
                $table_screen->get_id(),
                $this->preference,
                $user
            )
        );

        $list_screen = $request->get('list_screen');

        do_action('ac/table/screen', $table_screen, $list_screen);

        if ($list_screen instanceof ListScreen) {
            $this->preference->save(
                $table_screen->get_id(),
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
            $this->plugin,
            $table_screen,
            $list_screen
        );

        do_action('ac/table', $table);

        $table->register();
    }

}