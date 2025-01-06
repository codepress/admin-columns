<?php

declare(strict_types=1);

namespace AC;

use AC\Entity\Plugin;
use AC\Plugin\Version;

final class AdminColumns extends Plugin
{

    public function __construct(string $file, Version $version, bool $network_active = false)
    {
        parent::__construct(
            plugin_basename($file),
            plugin_dir_path($file),
            plugin_dir_url($file),
            $version,
            $network_active
        );
    }

}