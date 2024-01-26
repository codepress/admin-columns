<?php

namespace AC\Table\TableScreenRepository;

use AC\Table\TableScreenCollection;

interface Sort
{

    public function sort(TableScreenCollection $collection): TableScreenCollection;

}