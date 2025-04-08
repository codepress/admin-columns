<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Column;

interface ConditionalContextFactory extends ContextFactory
{

    public function supports(Column $column): bool;

}