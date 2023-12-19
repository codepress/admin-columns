<?php

declare(strict_types=1);

namespace AC;

use AC\TableScreen\ManageValue;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;

abstract class TableScreen implements ManageValue
{

    protected $key;

    protected $screen_id;

    protected $network;

    protected $columns;

    public function __construct(ListKey $key, string $screen_id, array $columns, bool $network = false)
    {
        $this->key = $key;
        $this->screen_id = $screen_id;
        $this->network = $network;
        $this->columns = $columns;
    }

    abstract public function get_heading_hookname(): string;

    abstract public function get_labels(): Labels;

    abstract public function get_query_type(): string;

    abstract public function get_attr_id(): string;

    abstract public function get_url(): Uri;

    public function get_key(): ListKey
    {
        return $this->key;
    }

    public function is_network(): bool
    {
        return $this->network;
    }

    public function get_screen_id(): string
    {
        return $this->screen_id;
    }

    public function get_columns(): array
    {
        return $this->columns;
    }

}