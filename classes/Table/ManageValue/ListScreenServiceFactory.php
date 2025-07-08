<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC;
use AC\ListScreen;
use AC\Registerable;
use AC\TableScreen;

class ListScreenServiceFactory implements AC\Table\ManageValueServiceFactory
{

    private TableScreen\ManageValueServiceFactory $factory;

    private AC\Table\ListScreenRenderableFactory $renderable_factory;

    public function __construct(
        TableScreen\ManageValueServiceFactory $factory,
        AC\Table\ListScreenRenderableFactory $renderable_factory
    ) {
        $this->factory = $factory;
        $this->renderable_factory = $renderable_factory;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        if ( ! $this->factory->can_create($table_screen)) {
            return null;
        }

        return $this->factory->create(
            $table_screen,
            $this->renderable_factory->create($list_screen)
        );
    }

}