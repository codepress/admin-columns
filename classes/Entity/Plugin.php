<?php

declare(strict_types=1);

namespace AC\Entity;

use AC\Asset\Location\Absolute;
use AC\Plugin\Version;
use AC\PluginUpdate;

class Plugin
{

    private $file;

    private $version;

    public function __construct(string $file, Version $version)
    {
        $this->file = $file;
        $this->version = $version;
    }

    public function get_version(): Version
    {
        return $this->version;
    }

    public function get_basename(): string
    {
        return plugin_basename($this->file);
    }

    public function get_dirname(): string
    {
        return dirname($this->get_basename());
    }

    public function get_dir(): string
    {
        return plugin_dir_path($this->file);
    }

    public function get_url(): string
    {
        return plugin_dir_url($this->file);
    }

    public function is_installed(): bool
    {
        return null !== $this->get_header_data();
    }

    public function is_active(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        return is_plugin_active($this->get_basename());
    }

    public function is_network_active(): bool
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        return is_plugin_active_for_network($this->get_basename());
    }

    public function get_name(): ?string
    {
        return $this->get_header('Name');
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

        $basename = $this->get_basename();
        if ( ! array_key_exists($basename, $updates)) {
            return null;
        }

        $data = $updates[$basename];

        if ( ! property_exists($data, 'update')) {
            return null;
        }

        if ( ! property_exists($data->update, 'new_version')) {
            return null;
        }

        $version = new Version($data->update->new_version);

        if ( ! $version->is_valid() || $version->is_lte($this->get_version())) {
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
        $basename = $this->get_basename();

        return $plugins && isset($plugins[$basename])
            ? (array)$plugins[$basename]
            : null;
    }

    public function get_header(string $var): ?string
    {
        $info = $this->get_header_data();

        return $info && isset($info[$var])
            ? (string)$info[$var]
            : null;
    }

    public function get_location(): Absolute
    {
        return new Absolute(
            $this->get_url(),
            $this->get_dir()
        );
    }

}