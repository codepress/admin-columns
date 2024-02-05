<?php

namespace AC;

interface ColumnFactories
{

    public function create(TableScreen $table_screen): ?Collection\ColumnFactories;

}