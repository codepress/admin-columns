<?php

declare(strict_types=1);

namespace AC\Table\ManageValue;

use AC\CellRenderer;
use AC\ListScreen;
use AC\Setting\ContextFactory;

class ListScreenRenderableFactory
{

    private ContextFactory $context_factory;

    public function __construct(ContextFactory $context_factory)
    {
        $this->context_factory = $context_factory;
    }

    public function create(Listscreen $list_screen): CellRenderer
    {
        return new ListScreenRenderable($list_screen, $this->context_factory);
    }

}