<?php

namespace AC;

use AC\Type\Url;

abstract class Integration
{

    private $slug;

    private $title;

    private $logo;

    private $url;

    private $plugin_link;

    private $description;

    public function __construct(
        string $slug,
        string $title,
        string $logo,
        string $description,
        Url $plugin_link = null,
        Url $url = null
    ) {
        if (null === $plugin_link) {
            $plugin_link = new Url\PluginSearch($title);
        }

        if (null === $url) {
            $url = new Url\UtmTags(new Url\Site(Url\Site::PAGE_PRICING), 'addon', $slug);
        }

        $this->slug = $slug;
        $this->title = $title;
        $this->logo = $logo;
        $this->description = $description;
        $this->plugin_link = $plugin_link;
        $this->url = $url;
    }

    abstract public function is_plugin_active(): bool;

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

    public function get_link(): string
    {
        return $this->url->get_url();
    }

    public function get_plugin_link(): string
    {
        return $this->plugin_link->get_url();
    }

    /**
     * Determines when the placeholder column is shown for a particular list screen.
     */
    public function show_placeholder(ListScreen $list_screen): bool
    {
        return true;
    }

}