<?php

namespace AC;

use AC\Type\ListKey;

class DefaultColumnsRepository
{

    private $key;

    public function __construct(ListKey $key)
    {
        $this->key = $key;
    }

    private function option_name(): string
    {
        return sprintf('cpac_options_%s__default', $this->key);
    }

    public function update(array $columns): void
    {
        update_option($this->option_name(), $columns, false);
    }

    public function exists(): bool
    {
        return false !== get_option($this->option_name());
    }

    public function get(): array
    {
        return get_option($this->option_name(), []);
    }

    public function delete(): void
    {
        delete_option($this->option_name());
    }

}