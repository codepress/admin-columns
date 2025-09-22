<?php

namespace AC;

use AC\Type\TableIdCollection;

interface TableIdsFactory
{

    public function create(): TableIdCollection;

}