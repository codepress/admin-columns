<?php

namespace AC\Service;

use AC\Capabilities;
use AC\Registerable;
use AC\Request;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\TableScreenFactory;
use WP_Screen;

// TODO remove
class DefaultColumns implements Registerable
{

    public const QUERY_PARAM = 'save-default-headings';

    private $table_screen;

    private $table_screen_factory;

    private $default_columns_repository;

    public function __construct(
        TableScreenFactory $table_screen_factory,
        DefaultColumnsRepository $default_columns_repository
    ) {
        $this->table_screen_factory = $table_screen_factory;
        $this->default_columns_repository = $default_columns_repository;
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

        $table_screen = $this->table_screen_factory->create_from_wp_screen($screen);

        $factory = SaveHeadings::get_factory($this->table_screen);

        if ( ! $factory || ! $factory->can_create($table_screen)) {
            return;
        }

        // Save an empty array in case the hook does not run properly.
        $this->default_columns_repository->update($this->table_screen->get_key(), []);

        $service = $factory->create($table_screen);

        $service->register();

        // Save an empty array in case the hook does not run properly.
        $this->default_columns_repository->update($this->table_screen->get_key(), []);

        // TODO

        // Our custom columns are set at priority 200. Before they are added we need to store the default column headings.
        add_filter($this->table_screen->get_heading_hookname(), [$this, 'save_headings'], 199);

        // no render needed
        ob_start();
    }

    public function save_headings($columns): void
    {
        ob_end_clean();

        $this->default_columns_repository->update(
            $this->table_screen->get_key(),
            $columns && is_array($columns)
                ? $columns
                : []
        );

        exit('ac_success');
    }

}