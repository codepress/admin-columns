<?php

namespace AC\Plugin;

abstract class Update
{

    protected Version $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function needs_update(Version $current_version): bool
    {
        return $this->version->is_gt($current_version);
    }

    abstract public function apply_update(): void;

    public function get_version(): Version
    {
        return $this->version;
    }

}