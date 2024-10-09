<?php

declare(strict_types=1);

namespace AC\Table\SaveHeading;

use AC\Registerable;
use AC\Storage\Repository\DefaultColumnsRepository;
use AC\Table\SaveHeadingFactory;
use AC\TableScreen;

class WpListTableFactory implements SaveHeadingFactory
{

    private DefaultColumnsRepository $repository;

    public function __construct(DefaultColumnsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function can_create(TableScreen $table_screen): bool
    {
        return $table_screen instanceof TableScreen\Post ||
               $table_screen instanceof TableScreen\User ||
               $table_screen instanceof TableScreen\Comment ||
               $table_screen instanceof TableScreen\Media;
    }

    public function create(TableScreen $table_screen): ?Registerable
    {
        return new TableScreen\SaveHeading\WpListTable(
            $table_screen->get_screen_id(),
            $table_screen->get_key(),
            $this->repository
        );
    }

}