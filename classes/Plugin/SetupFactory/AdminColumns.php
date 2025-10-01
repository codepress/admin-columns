<?php

declare(strict_types=1);

namespace AC\Plugin\SetupFactory;

use AC\Plugin\Install;
use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\SetupFactory;
use AC\Plugin\Update;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Storage\Table;

final class AdminColumns extends SetupFactory
{

    private Table\AdminColumns $table;

    public function __construct(
        string $version_key,
        Version $version,
        Table\AdminColumns $table,
        ?InstallCollection $installers = null,
        ?UpdateCollection $updates = null
    ) {
        parent::__construct($version_key, $version, $installers, $updates);

        $this->table = $table;
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
                    new Install\Database($this->table),
                ]);

                $this->updates = new UpdateCollection([
                    new Update\V4000(),
                    new Update\V7000(new Install\Database($this->table)),
                ]);

                break;
        }

        return parent::create($type);
    }

}