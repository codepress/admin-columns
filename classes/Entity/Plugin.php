<?php

declare(strict_types=1);

namespace AC\Entity;

use AC\Plugin\Version;
use AC\PluginUpdate;

class Plugin
{

    private string $basename;

    private string $dir;

    private string $url;

    private Version $version;

    public function __construct(string $basename, string $dir, string $url, Version $version)
    {
        $this->basename = $basename;
        $this->dir = $dir;
        $this->url = $url;
        $this->version = $version;
    }

    public static function create(string $file, Version $version): self
    {
        return new self(
            plugin_basename($file),
            plugin_dir_path($file),
            plugin_dir_url($file),
            $version
        );
    }

    public function get_version(): Version
    {
        return $this->version;
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

    public function get_dirname(): string
    {
        return dirname($this->basename);
    }

    public function is_installed(): bool
    {
        return null !== $this->get_header_data();
    }

    public function is_active(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        return is_plugin_active($this->basename);
    }

    public function is_network_active(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        return is_plugin_active_for_network($this->basename);
    }

    public function get_name(): ?string
    {
        return $this->get_header_var('Name');
    }

    private function get_plugins(): array
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        // use `get_plugins` (cached) over `get_plugin_data` (non cached)
        return get_plugins();
    }

    private function get_plugin_updates(): array
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        return get_plugin_updates();
    }

    public function has_update(): bool
    {
        return null !== $this->get_update();
    }

    public function get_update(): ?PluginUpdate
    {
        $updates = $this->get_plugin_updates();

        if ( ! array_key_exists($this->basename, $updates)) {
            return null;
        }

        $data = $updates[$this->basename];

        if ( ! property_exists($data, 'update')) {
            return null;
        }

        if ( ! property_exists($data->update, 'new_version')) {
            return null;
        }

        $version = new Version($data->update->new_version);

        if ( ! $version->is_valid() || $version->is_lte($this->version)) {
            return null;
        }

        $package = property_exists($data->update, 'package') && $data->update->package
            ? $data->update->package
            : null;

        return new PluginUpdate(new Version($data->update->new_version), $package);
    }

    private function get_header_data(): ?array
    {
        $plugins = $this->get_plugins();

        return $plugins[$this->basename] ?? null;
    }

    private function get_header_var(string $var): ?string
    {
        return $this->get_header_data()[$var] ?? null;
    }

}