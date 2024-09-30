<?php

declare(strict_types=1);

namespace AC\Table\Service;

use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\PostType;
use AC\Registerable;
use AC\Request;
use AC\Request\Middleware\ListScreenTable;
use AC\Table\LayoutPreference;
use AC\TableScreenFactory;
use WP_Screen;

// TODO Proof-of-concept
class ColumnRenderer implements Registerable
{

    private Storage $storage;

    private LayoutPreference $preference;

    private TableScreenFactory $table_screen_factory;

    public function __construct(
        Storage $storage,
        LayoutPreference $preference,
        TableScreenFactory $table_screen_factory,
    ) {
        $this->storage = $storage;
        $this->preference = $preference;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function register(): void
    {
        add_action('current_screen', [$this, 'register_services']);
    }

    public function register_services(WP_Screen $screen): void
    {
        if ( ! $this->table_screen_factory->can_create_from_wp_screen($screen)) {
            return;
        }

        $table_screen = $this->table_screen_factory->create_from_wp_screen($screen);

        $request = new Request();
        $request->add_middleware(
            new ListScreenTable(
                $this->storage,
                $table_screen->get_key(),
                $this->preference
            )
        );

        $list_screen = $request->get('list_screen');

        if ( ! $list_screen instanceof ListScreen) {
            return;
        }

        if ($table_screen instanceof PostType) {
            (new Posts($table_screen->get_post_type(), $list_screen->get_columns()))->register();
        }
    }

}