<?php

declare(strict_types=1);

namespace AC\Service;

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

    public function get_manage_column_service(TableScreen $table_screen): ?Registerable
    {
        foreach (array_reverse(self::$factories) as $factory) {
            if ($factory->can_create($table_screen)) {
                return $factory->create($table_screen);
            }
        }

        return null;
    }

    public function handle(TableScreen $table_screen): void
    {
        $request = new Request();

        if ('1' !== $request->get('save-default-headings')) {
            return;
        }

        // Save an empty array in case the hook does not run properly.
        $this->repository->update($table_screen->get_id(), new OriginalColumns());

        $service = $this->get_manage_column_service($table_screen);

        ob_start(); // prevent any output

        if ($service) {
            $service->register();
        }
    }

}