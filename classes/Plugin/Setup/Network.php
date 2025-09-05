<?php

namespace AC\Plugin\Setup;

use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Storage\SiteOption;

final class Network extends Setup
{

    public function __construct(
        SiteOption $storage,
        Version $version,
        ?InstallCollection $installers = null,
        ?UpdateCollection $updates = null
    ) {
        parent::__construct($storage, $version, $installers, $updates);
    }

    protected function is_new_install(): bool
    {
        $result = get_site_option('cpupdate_cac-pro');

        if ($result) {
            return false;
        }

        return ! $this->get_stored_version()->is_valid();
    }

}