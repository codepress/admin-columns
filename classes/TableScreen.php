<?php

declare(strict_types=1);

namespace AC;

use AC\Type\Labels;
use AC\Type\TableId;
use AC\Type\Uri;
use AC\Type\Url;

class TableScreen
{

    protected TableId $id;

    protected string $screen_id;

    protected Labels $labels;

    private Url $url;

    protected bool $network;

    private ?string $attr_id;

    public function __construct(
        TableId $id,
        string $screen_id,
        Labels $labels,
        Uri $url,
        ?string $attr_id = null,
        bool $network = false
    ) {
        $this->id = $id;
        $this->screen_id = $screen_id;
        $this->labels = $labels;
        $this->url = $url;
        $this->network = $network;
        $this->attr_id = $attr_id ?? '#the-list';
    }

    public function get_url(): Uri
    {
        return $this->url;
    }

    public function get_id(): TableId
    {
        return $this->id;
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