<?php

namespace AC\Plugin\SetupFactory;

use AC\Plugin\Install;
use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\SetupFactory;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;

final class AdminColumns extends SetupFactory
{

    public function __construct(
        string $version_key,
        Version $version,
        InstallCollection $installers = null,
        UpdateCollection $updates = null
    ) {
        parent::__construct($version_key, $version, $installers, $updates);
    }

    public function create(string $type): Setup
    {
        switch ($type) {
            case self::NETWORK:
                $this->installers = new InstallCollection([
                    new Install\Capabilities(),
                ]);
                break;
            case self::SITE:
                $this->installers = new InstallCollection([
                    new Install\Capabilities(),
                    new Install\Database(),
                ]);
                $this->updates = new UpdateCollection([
                    new Update\V4000(),
                    new Update\V5000(),
                ]);
                break;
        }

        return parent::create($type);
    }

}