<?php

namespace AC\Controller;

use AC\Asset\Location\Absolute;
use AC\ColumnSize;
use AC\ListScreen;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Request;
use AC\Settings\General\EditButton;
use AC\Table;
use WP_Screen;

class TableListScreenSetter implements Registerable
{

    private $storage;

    private $location;

    private $list_screen_factory;

    private $preference;

    private $primary_column_factory;

    private $edit_button;

    public function __construct(
        Storage $storage,
        Absolute $location,
        ListScreenFactory $list_screen_factory,
        Table\LayoutPreference $preference,
        Table\PrimaryColumnFactory $primary_column_factory,
        EditButton $edit_button
    ) {
        $this->storage = $storage;
        $this->list_screen_factory = $list_screen_factory;
        $this->location = $location;
        $this->preference = $preference;
        $this->primary_column_factory = $primary_column_factory;
        $this->edit_button = $edit_button;
    }

    public function register(): void
    {
        add_action('current_screen', [$this, 'handle']);
    }

    public function handle(WP_Screen $wp_screen): void
    {
        $request = new Request();

        $request->add_middleware(
            new Middleware\ListScreenTable(
                $this->storage,
                $this->list_screen_factory,
                $wp_screen,
                $this->preference
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            return;
        }

        if ($list_screen->has_id()) {
            $this->preference->set($list_screen->get_key(), (string)$list_screen->get_id());
        }

        $table_screen = new Table\Screen(
            $this->location,
            $list_screen,
            new ColumnSize\ListStorage($this->storage),
            new ColumnSize\UserStorage(new ColumnSize\UserPreference()),
            $this->primary_column_factory,
            $this->edit_button
        );
        $table_screen->register();

        do_action('ac/table', $table_screen);
    }

}