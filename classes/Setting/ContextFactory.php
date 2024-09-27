<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Column;

interface ContextFactory
{

    public function create(Column $column): Context;

}