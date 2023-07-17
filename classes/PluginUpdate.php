<?php

namespace AC;

use AC\Plugin\Version;

// TODO what does this do?
class PluginUpdate
{

    /**
     * @var Version
     */
    private $version;

    /**
     * @var string|null
     */
    private $package;

    public function __construct(Version $version, string $package = null)
    {
        $this->version = $version;
        $this->package = $package;
    }

    public function get_version(): Version
    {
        return $this->version;
    }

    public function has_package(): bool
    {
        return null !== $this->package;
    }

    public function get_package(): string
    {
        return $this->package;
    }

}