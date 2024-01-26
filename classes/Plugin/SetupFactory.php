<?php

namespace AC\Plugin;

use AC\Storage\Option;
use AC\Storage\SiteOption;
use InvalidArgumentException;

class SetupFactory
{

    public const SITE = 'site';
    public const NETWORK = 'network';

    private $version_key;

    private $version;

    protected $installers;

    protected $updates;

    public function __construct(
        string $version_key,
        Version $version,
        InstallCollection $installers = null,
        UpdateCollection $updates = null
    ) {
        $this->version_key = $version_key;
        $this->version = $version;
        $this->installers = $installers;
        $this->updates = $updates;
    }

    public function create(string $type): Setup
    {
        $installers = $this->installers ?: new InstallCollection();
        $updates = $this->updates ?: new UpdateCollection();

        switch ($type) {
            case self::NETWORK:
                return new Setup\Network(
                    new SiteOption($this->version_key),
                    $this->version,
                    $installers,
                    $updates
                );

            case self::SITE:
                return new Setup\Site(
                    new Option($this->version_key),
                    $this->version,
                    $installers,
                    $updates
                );

            default:
                throw new InvalidArgumentException('Expected valid setup type.');
        }
    }

}