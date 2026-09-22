<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Capabilities;
use AC\Registerable;
use AC\Request;
use AC\Storage\Repository\OriginalColumnsRepository;
use AC\Table\SaveHeadingFactory;
use AC\TableScreen;
use AC\Type\OriginalColumns;

class SaveHeadings implements Registerable
{
    private static array $factories = [];

    private OriginalColumnsRepository $repository;

    public function __construct(OriginalColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function add(SaveHeadingFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/screen', [$this, 'handle']);
    }

    public function get_manage_column_service(TableScreen $table_screen, bool $do_exit = true): ?Registerable
    {
        foreach (array_reverse(self::$factories) as $factory) {
            if ($factory->can_create($table_screen)) {
                return $factory->create($table_screen, $do_exit);
            }
        }

        return null;
    }

    public function handle(TableScreen $table_screen): void
    {
        $request = new Request();

        if ('1' === $request->get('save-default-headings')) {
            $this->save_on_request($table_screen);

            return;
        }

        $this->save_on_table($table_screen, $request);
    }

    private function save_on_request(TableScreen $table_screen): void
    {
        // Save an empty array in case the hook does not run properly.
        $this->repository->update($table_screen->get_id(), new OriginalColumns());

        $service = $this->get_manage_column_service($table_screen);

        ob_start(); // prevent any output

        if ($service) {
            $service->register();
        }
    }

    /**
     * Only the settings page requests the headings. Configurations that never pass through that page,
     * like file based storage, leave the table without its original columns.
     */
    private function save_on_table(TableScreen $table_screen, Request $request): void
    {
        if (wp_doing_ajax() || $this->repository->is_complete($table_screen->get_id())) {
            return;
        }

        if (! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        if (! $this->is_default_view($table_screen, $request)) {
            return;
        }

        $service = $this->get_manage_column_service($table_screen, false);

        if ($service) {
            $service->register();
        }
    }

    /**
     * A filtered or searched table can hold fewer columns than the table itself. Only the plain view
     * describes the defaults, which is the view the settings page asks for.
     */
    private function is_default_view(TableScreen $table_screen, Request $request): bool
    {
        $arguments = [];

        parse_str(
            (string)wp_parse_url((string)$table_screen->get_url(), PHP_URL_QUERY),
            $arguments
        );

        foreach (array_keys($request->get_query()->all()) as $key) {
            if (! array_key_exists($key, $arguments)) {
                return false;
            }
        }

        return true;
    }

}
