<?php

declare(strict_types=1);

namespace AC;

// TODO Stefan make the name more affording E.g. ColumnFactoryCollectionFactory
interface ColumnFactories
{

    // TODO Stefan remove nullable
    public function create(TableScreen $table_screen): ?Collection\ColumnFactories;

}