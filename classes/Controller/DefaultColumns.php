<?php

namespace AC\Controller;

use AC\Capabilities;
use AC\DefaultColumnsRepository;
use AC\Registerable;
use AC\Request;
use AC\TableScreenFactory;
use WP_Screen;

class DefaultColumns implements Registerable
{

    public const QUERY_PARAM = 'save-default-headings';

    private $table_screen;

    private $default_columns;

    private $table_screen_factory;

    public function __construct(
        TableScreenFactory $table_screen_factory,
        DefaultColumnsRepository $default_columns
    ) {
        $this->default_columns = $default_columns;
        $this->table_screen_factory = $table_screen_factory;
    }

    public function register(): void
    {
        add_action('current_screen', [$this, 'handle_request']);
    }

    public function handle_request(): void
    {
        $request = new Request();

        if ('1' !== $request->get(self::QUERY_PARAM)) {
            return;
        }

        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $screen = get_current_screen();

        if ( ! $screen instanceof WP_Screen) {
            return;
        }

        if ( ! $this->table_screen_factory->can_create_from_wp_screen($screen)) {
            return;
        }

        $this->table_screen = $this->table_screen_factory->create_from_wp_screen($screen);

        // Save an empty array in case the hook does not run properly.
        $this->default_columns->update($this->table_screen->get_key(), []);

        // Our custom columns are set at priority 200. Before they are added we need to store the default column headings.
        add_filter($this->table_screen->get_heading_hookname(), [$this, 'save_headings'], 199);

        // no render needed
        ob_start();
    }

    public function save_headings($columns): void
    {
        ob_end_clean();

        $this->default_columns->update($this->table_screen->get_key(), $columns && is_array($columns) ? $columns : []);

        exit('ac_success');
    }

}