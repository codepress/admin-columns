<?php

namespace AC\Table\TableScreenRepository;

use AC\Table\TableScreenCollection;

interface Filter
{

    public function filter(TableScreenCollection $collection): TableScreenCollection;

}