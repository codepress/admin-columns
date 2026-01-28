<?php

namespace AC\Admin;

abstract class HelpTab
{

    private string $id;

    private string $title;

    public function __construct(string $id, string $title)
    {
        $this->id = 'ac-tab-' . $id;
        $this->title = $title;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function get_id(): string
    {
        return $this->id;
    }

    abstract public function get_content(): string;

}