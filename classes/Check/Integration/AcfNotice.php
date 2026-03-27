<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class AcfNotice implements IntegrationNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('acf', false) && ! class_exists('ACF', false)) {
            return false;
        }

        return $screen->get_id() === 'acf-field-group';
    }

    public function get_slug(): string
    {
        return 'acf';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-acf';
    }

    public function get_eyebrow(): string
    {
        return '';
    }

    public function get_title(): string
    {
        return __('Use these fields directly in your list tables!', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Show ACF fields as columns so you can sort, filter, and edit them directly from your list table.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Add ACF columns', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('Learn more', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function get_delay_days(): int
    {
        return 42;
    }

}
