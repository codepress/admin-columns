<?php

declare(strict_types=1);

namespace AC;

use AC\ListScreen\ManageValue;
use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;

abstract class TableScreen implements ManageValue
{

    protected $key;

    protected $screen_id;

    protected $network;

    public function __construct(ListKey $key, string $screen_id, bool $network)
    {
        $this->key = $key;
        $this->screen_id = $screen_id;
        $this->network = $network;
    }

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

    public function get_heading_hookname(): string
    {
        return sprintf('manage_%s_columns', $this->screen_id);
    }

    abstract public function get_labels(): Labels;

    abstract public function get_query_type(): string;

    abstract public function get_meta_type(): MetaType;

    abstract public function get_attr_id(): string;

    abstract public function get_url(): Uri;

    // TODO move out of this scope
    abstract public function get_group(): string;

}