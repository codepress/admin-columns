<?php

declare(strict_types=1);

namespace AC\Settings;

use AC\Expression\Specification;
use AC\Setting\Config;

interface SettingFactory
{

    public static function create(Config $config, Specification $specification = null): Column;

}