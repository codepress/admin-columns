<?php

namespace AC\Type;

use AC\Screen;
use AC\TableScreen;

abstract class Integration
{

    private string $slug;

    private string $title;

    private string $logo;

    private Url $url;

    private Url $plugin_link;

    private string $description;

    /** @var string[] */
    private array $features;

    private string $audience;

    public function __construct(
        string $slug,
        string $title,
        string $logo,
        string $description,
        ?Url $plugin_link = null,
        ?Url $url = null,
        array $features = [],
        string $audience = ''
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
        $this->features = $features;
        $this->audience = $audience;
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

    public function get_url(): Url
    {
        return $this->url;
    }

    public function get_plugin_link(): string
    {
        return $this->plugin_link->get_url();
    }

    /**
     * @return string[]
     */
    public function get_features(): array
    {
        return $this->features;
    }

    public function get_audience(): string
    {
        return $this->audience;
    }

    /**
     * Determines when the placeholder column is shown for a particular list screen.
     */
    public function show_placeholder(TableScreen $table_screen): bool
    {
        return true;
    }

}