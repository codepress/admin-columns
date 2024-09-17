<?php

declare(strict_types=1);

namespace AC\Table;

// TODO remove this class. use ManageValueFactory instead.
abstract class ManageValue
{

    abstract public function register(): void;

}