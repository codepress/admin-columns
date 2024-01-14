<?php

declare(strict_types=1);

namespace AC\Storage\Encoder;

use AC;
use AC\ListScreen;
use AC\Plugin\Version;

class BaseEncoder implements AC\Storage\Encoder
{

    private $version;

    /**
     * @var ListScreen
     */
    private $list_screen;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function set_list_screen(ListScreen $list_screen): self
    {
        $this->list_screen = $list_screen;

        return $this;
    }

    public function encode(): array
    {
        $encoded_data = [
            'version' => (string)$this->version,
        ];

        if ($this->list_screen instanceof ListScreen) {
            $encoded_data['list_screen'] = [
                'title'    => $this->list_screen->get_title(),
                'type'     => (string)$this->list_screen->get_key(),
                'id'       => (string)$this->list_screen->get_id(),
                'updated'  => $this->list_screen->get_updated()->getTimestamp(),
                'columns'  => $this->get_columns(),
                'settings' => $this->get_preferences(),
            ];
        }

        return $encoded_data;
    }

    protected function get_preferences(): array
    {
        return $this->list_screen->get_preferences();
    }

    private function get_columns(): array
    {
        $encode = [];

        foreach ($this->list_screen->get_columns() as $column) {
            $encode[$column->get_name()] = $column->toArray();
        }

        return $encode;
    }
}