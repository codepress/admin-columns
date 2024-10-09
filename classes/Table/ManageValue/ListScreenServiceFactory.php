<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC;
use AC\ListScreen;
use AC\Registerable;
use AC\Setting\ContextFactory;
use AC\TableScreen;

class ListScreenServiceFactory implements AC\Table\ManageValueServiceFactory
{

    private TableScreen\ManageValueFactory $factory;

    private ContextFactory\Column $context_factory;

    public function __construct(
        TableScreen\ManageValueFactory $factory,
        ContextFactory\Column $context_factory
    ) {
        $this->factory = $factory;
        $this->context_factory = $context_factory;
    }

    public function create(TableScreen $table_screen, ListScreen $list_screen): ?Registerable
    {
        if ( ! $this->factory->can_create($table_screen)) {
            return null;
        }

        return $this->factory->create(
            $table_screen,
            new ListScreenRenderable($list_screen, $this->context_factory)
        );
    }

}