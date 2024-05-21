<?php

namespace AC;

// TODO Stefan make the name more affording E.g. ColumnFactoryCollectionFactory
interface ColumnFactories
{

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories;

}