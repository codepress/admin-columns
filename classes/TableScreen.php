<?php

declare(strict_types=1);

namespace AC;

use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Uri;

abstract class TableScreen
{

    protected ListKey $key;

    protected string $screen_id;

    protected bool $network;

    private Labels $labels;

    public function __construct(ListKey $key, string $screen_id, Labels $labels, bool $network = false)
    {
        $this->key = $key;
        $this->screen_id = $screen_id;
        $this->network = $network;
        $this->labels = $labels;
    }

    // TODO remove. check overrides
    public function get_heading_hookname(): string
    {
        return '';
    }

    public function get_labels(): Labels
    {
        return $this->labels;
    }

    // TODO should this be a string without context?

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

}