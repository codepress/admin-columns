<?php

declare(strict_types=1);

namespace AC\Table\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Table\SaveHeadingFactory;
use AC\TableScreen;

abstract class ScreenColumnsFactory implements SaveHeadingFactory
{

    private DefaultColumnsRepository $repository;

    public function __construct(DefaultColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(TableScreen $table_screen): ?Registerable
    {
        return new TableScreen\SaveHeading\ScreenColumns(
            $table_screen->get_screen_id(),
            $table_screen->get_key(),
            $this->repository
        );
    }

}