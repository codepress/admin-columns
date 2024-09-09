<?php

namespace AC\Plugin\SetupFactory;

use AC\Asset\Location\Absolute;
use AC\NoticeRepository;
use AC\Plugin\Install;
use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\SetupFactory;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Storage\OptionFactory;

final class AdminColumns extends SetupFactory
{

    private $location;

    public function __construct(
        string $version_key,
        Version $version,
        Absolute $location,
        InstallCollection $installers = null,
        UpdateCollection $updates = null
    ) {
        parent::__construct($version_key, $version, $installers, $updates);

        $this->location = $location;
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
                    new Install\Notifications(new NoticeRepository(new OptionFactory())),
                ]);
                $this->updates = new UpdateCollection([
                    new Update\V3005(),
                    new Update\V3007(),
                    new Update\V3201($this->location),
                    new Update\V4000(),
                ]);
                break;
        }

        return parent::create($type);
    }

}