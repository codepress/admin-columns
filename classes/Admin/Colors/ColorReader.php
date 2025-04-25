<?php

declare(strict_types=1);

namespace AC\Admin\Colors;

use AC\Admin\Colors\Type\Color;

interface ColorReader
{

    public function find_all(): ColorCollection;

    public function find_with_name(string $name): ?Color;

}