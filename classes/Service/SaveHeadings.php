<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;
use AC\Request;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Table\SaveHeadingFactory;
use AC\TableScreen;

class SaveHeadings implements Registerable
{

    private static array $factories = [];

    private DefaultColumnsRepository $repository;

    public function __construct(DefaultColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function add(SaveHeadingFactory $factory): void
    {
        self::$factories[] = $factory;
    }

    public function register(): void
    {
        add_action('ac/table/screen', [$this, 'handle'], 10, 2);
    }

    public function get_factory(TableScreen $table_screen): ?SaveHeadingFactory
    {
        foreach (array_reverse(self::$factories) as $factory) {
            if ($factory->can_create($table_screen)) {
                return $factory;
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
        $this->repository->update($table_screen->get_key(), []);

        $factory = $this->get_factory($table_screen);

        if ($factory) {
            $factory->create($table_screen)
                    ->register();
        }
    }

}