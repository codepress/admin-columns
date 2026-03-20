<?php

declare(strict_types=1);

namespace AC\ListScreenRepository;

use AC\ListScreenCollection;

interface Sort
{

    public function sort(ListScreenCollection $list_screens): ListScreenCollection;

}