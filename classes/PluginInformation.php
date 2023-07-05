<?php

namespace AC;

use AC\Plugin\Version;

class PluginInformation
{

    private $basename;

    public function __construct(string $basename)
    {
        $this->basename = $basename;
    }

    public static function create_by_file(string $file): self
    {
        return new self(plugin_basename($file));
    }

    public function get_basename(): string
    {
        return $this->basename;
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

    public function get_version(): Version
    {
        return new Version((string)$this->get_header('Version'));
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

        return $plugins && isset($plugins[$this->basename])
            ? (array)$plugins[$this->basename]
            : null;
    }

    public function get_header(string $var): ?string
    {
        $info = $this->get_header_data();

        return $info && isset($info[$var])
            ? (string)$info[$var]
            : null;
    }

}