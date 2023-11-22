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

    public function update(array $columns): void
    {
        update_option($this->option_name(), $columns, false);
    }

    public function exists(): bool
    {
        return false !== get_option($this->option_name());
    }

    public function delete(): void
    {
        delete_option($this->option_name());
    }

    /**
     * @return Column[]
     */
    public function find_all(): array
    {
        $columns = [];

        // Add original WP columns
        foreach ($this->get() as $type => $label) {
            if ('cb' === $type) {
                continue;
            }

            $columns[$type] = (new Column())->set_type($type)
                                            ->set_label($label)
                                            ->set_group('default')
                                            ->set_original(true);
        }

        return $columns;
    }

    private function get(): array
    {
        return get_option($this->option_name(), []);
    }

    private function option_name(): string
    {
        return sprintf('cpac_options_%s__default', $this->key);
    }

}