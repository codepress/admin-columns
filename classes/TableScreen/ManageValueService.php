<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC\Registerable;

interface ManageValueService extends Registerable
{

    // The simplest commonality between WordPress tables
    public function render_value(...$args);

}