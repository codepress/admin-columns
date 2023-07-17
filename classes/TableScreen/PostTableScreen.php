<?php

declare(strict_types=1);

namespace AC\TableScreen;

use AC\ListTableFactory;
use AC\Type\Url;

// TODO
class PostTableScreen
{

    private $url;

    private $label;

    private $list_table_factory;

    public function __construct(Url $url, string $label, ListTableFactory $list_table_factory)
    {
        $this->url = $url;
        $this->label = $label;
        $this->list_table_factory = $list_table_factory;
    }

    public function get_url(): Url
    {
        return $this->url;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    // TODO add render_cell to `ListTable`
    public function get_list_table_factory(): ListTableFactory
    {
        return $this->list_table_factory;
    }

}