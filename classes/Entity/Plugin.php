<?php

declare(strict_types=1);

namespace AC\Entity;

use AC\Asset\Location;
use AC\Plugin\Version;

class Plugin
{

    private string $basename;

    private string $dir;

    private string $url;

    private Version $version;

    public function __construct(
        string $basename,
        string $dir,
        string $url,
        Version $version
    ) {
        $this->basename = $basename;
        $this->dir = $dir;
        $this->url = $url;
        $this->version = $version;
    }

    public static function from_plugin_file(string $file, Version $version): self
    {
        return new self(
            plugin_basename($file),
            plugin_dir_path($file),
            plugin_dir_url($file),
            $version
        );
    }

    public function get_basename(): string
    {
        return $this->basename;
    }

    public function get_dir(): string
    {
        return $this->dir;
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function get_location(): Location
    {
        return new Location\Absolute($this->url, $this->dir);
    }

    public function get_dirname(): string
    {
        return dirname($this->basename);
    }

    public function get_version(): Version
    {
        return $this->version;
    }

    public function is_network_active(): bool
    {
        return is_plugin_active_for_network($this->basename);
    }

}