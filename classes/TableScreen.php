<?php

declare(strict_types=1);

namespace AC;

use AC\Type\Labels;
use AC\Type\ListKey;
use AC\Type\Url;

class TableScreen
{

    protected ListKey $key;

    protected string $screen_id;

    protected Labels $labels;

    private Url $url;

    protected bool $network;

    private ?string $attr_id;

    public function __construct(
        ListKey $key,
        string $screen_id,
        Labels $labels,
        Url $url,
        string $attr_id = null,
        bool $network = false
    ) {
        $this->key = $key;
        $this->screen_id = $screen_id;
        $this->labels = $labels;
        $this->url = $url;
        $this->network = $network;
        $this->attr_id = $attr_id ?? '#the-list';
    }

    public function get_url(): Url
    {
        return $this->url;
    }

    public function get_key(): ListKey
    {
        return $this->key;
    }

    public function get_labels(): Labels
    {
        return $this->labels;
    }

    public function get_screen_id(): string
    {
        return $this->screen_id;
    }

    public function is_network(): bool
    {
        return $this->network;
    }

    public function get_attr_id(): string
    {
        return $this->attr_id;
    }

}