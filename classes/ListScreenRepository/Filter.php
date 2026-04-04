<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreenCollection;

interface Filter
{

    public function filter(ListScreenCollection $list_screens): ListScreenCollection;

}