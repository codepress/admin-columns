<?php

declare(strict_types=1);

namespace AC\Table\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\OriginalColumnsRepository;
use AC\Table\SaveHeadingFactory;
use AC\TableScreen;

abstract class ScreenColumnsFactory implements SaveHeadingFactory
{

    private OriginalColumnsRepository $repository;

    public function __construct(OriginalColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(TableScreen $table_screen): ?Registerable
    {
        return new TableScreen\SaveHeading\ScreenColumns(
            $table_screen->get_screen_id(),
            $table_screen->get_id(),
            $this->repository
        );
    }

}