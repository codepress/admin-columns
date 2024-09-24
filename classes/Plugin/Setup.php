<?php

namespace AC\Plugin;

use AC\Storage\KeyValue;

abstract class Setup
{

    /**
     * @var KeyValue
     */
    private $storage;

    /**
     * @var Version
     */
    private $version;

    /**
     * @var InstallCollection
     */
    private $installers;

    /**
     * @var UpdateCollection
     */
    private $updates;

    public function __construct(
        KeyValue $storage,
        Version $version,
        InstallCollection $installers,
        UpdateCollection $updates
    ) {
        $this->storage = $storage;
        $this->version = $version;
        $this->installers = $installers;
        $this->updates = $updates;
    }

    protected function update_stored_version(Version $version): void
    {
        $this->storage->save((string)$version);
    }

    protected function get_stored_version(): Version
    {
        return new Version((string)$this->storage->get());
    }

    private function update_stored_version_to_current(): void
    {
        $this->update_stored_version($this->version);
    }

    abstract protected function is_new_install(): bool;

    private function install(): void
    {
        foreach ($this->installers as $installer) {
            $installer->install();
        }

        $this->update_stored_version_to_current();
    }

    private function update(): void
    {
        foreach ($this->updates as $update) {
            if ( ! $update->needs_update($this->get_stored_version())) {
                continue;
            }

            $update->apply_update();

            $this->update_stored_version($update->get_version());
        }

        $this->update_stored_version_to_current();
    }

    public function run(bool $force_install = false): void
    {
        if ($force_install === true) {
            $this->install();
        }

        if ($this->version->is_equal($this->get_stored_version())) {
            return;
        }

        if ($this->is_new_install()) {
            $this->install();
        } else {
            $this->update();
        }
    }

}