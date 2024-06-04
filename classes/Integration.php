<?php

namespace AC;

use AC\Type\Url;
use AC\Type\Url\UtmTags;

abstract class Integration
{

    private $slug;

    private $title;

    private $logo;

    private $description;

    private $url;

    public function __construct(
        string $slug,
        string $title,
        string $logo,
        string $description,
        Url $url
    ) {
        $this->slug = $slug;
        $this->title = $title;
        $this->logo = $logo;
        $this->description = $description;
        $this->url = new UtmTags($url, 'addon', $slug);
    }

    abstract public function is_plugin_active(): bool;

    abstract public function show_placeholder(ListScreen $list_screen): bool;

    abstract public function show_notice(Screen $screen): bool;

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function get_logo(): string
    {
        return $this->logo;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_url(): Url
    {
        return $this->url;
    }

}