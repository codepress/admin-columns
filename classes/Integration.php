<?php

namespace AC;

use AC\Type\Url;

abstract class Integration
{

    private $basename;

    private $title;

    private $logo;

    private $url;

    private $plugin_link;

    private $description;

    public function __construct(
        string $basename,
        string $title,
        string $logo,
        string $description,
        string $plugin_link = null,
        Url $url = null
    ) {
        if (null === $plugin_link) {
            $plugin_link = $this->search_plugin($title);
        }

        if (null === $url) {
            $url = new Url\Site(Url\Site::PAGE_PRICING);
        }

        $this->basename = $basename;
        $this->title = $title;
        $this->logo = $logo;
        $this->description = $description;
        $this->plugin_link = $plugin_link;
        $this->url = $url;
    }

    abstract public function is_plugin_active(): bool;

    // TODO move...
    abstract public function show_notice(Screen $screen): bool;

    private function search_plugin(string $name): string
    {
        // TODO turn into URL
        return add_query_arg(
            [
                'tab'  => 'search',
                'type' => 'term',
                's'    => $name,
            ],
            admin_url('plugin-install.php')
        );
    }

    public function get_basename(): string
    {
        return $this->basename;
    }

    public function get_slug(): string
    {
        return dirname($this->basename);
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
        return (new Url\UtmTags($this->url, 'addon', $this->get_slug()))->get_url();
    }

    public function get_plugin_link(): string
    {
        return $this->plugin_link;
    }

    /**
     * Determines when the placeholder column is shown for a particular list screen.
     */
    public function show_placeholder(ListScreen $list_screen): bool
    {
        return true;
    }

}