<?php

declare(strict_types=1);

namespace AC\Check\Suggestion;

use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\UtmTags;

class IntegrationNotice implements SuggestionNotice
{

    private Integration $integration;

    private string $icon;

    public function __construct(Integration $integration, string $icon)
    {
        $this->integration = $integration;
        $this->icon = $icon;
    }

    public function is_active(Screen $screen): bool
    {
        return $this->integration->is_plugin_active() && $this->integration->show_notice($screen);
    }

    public function get_slug(): string
    {
        return $this->integration->get_slug();
    }

    public function get_integration_slug(): string
    {
        return $this->integration->get_slug();
    }

    public function get_icon(): string
    {
        return $this->icon;
    }

    public function get_eyebrow(): string
    {
        return sprintf(
            __('Admin Columns Pro for %s', 'codepress-admin-columns'),
            $this->integration->get_title()
        );
    }

    public function get_title(): string
    {
        return sprintf(
            __('Get more out of %s with Admin Columns Pro', 'codepress-admin-columns'),
            $this->integration->get_title()
        );
    }

    public function get_description(): string
    {
        return $this->integration->get_description();
    }

    public function get_cta_label(): string
    {
        return sprintf('%s - %s', __('Upgrade', 'codepress-admin-columns'), '€79/year');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags($this->integration->get_url(), 'notice-' . $this->integration->get_slug()))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags($this->integration->get_url(), 'notice-' . $this->integration->get_slug() . '-features'))->get_url();
    }

}
