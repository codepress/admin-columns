<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class GravityFormsNotice implements IntegrationNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('GFCommon', false)) {
            return false;
        }

        return 'forms_page_gf_entries' === $screen->get_id();
    }

    public function get_slug(): string
    {
        return 'gravityforms';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-gravityforms';
    }

    public function get_icon(): string
    {
        return '📋';
    }

    public function get_eyebrow(): string
    {
        return __('Admin Columns Pro for Gravity Forms', 'codepress-admin-columns');
    }

    public function get_title(): string
    {
        return __('Still clicking into each entry to see what was submitted?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Display any form field as a column, sort and filter entries by any value, and export results to CSV - all from this screen.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return sprintf('%s - %s', __('Upgrade', 'codepress-admin-columns'), '€79/year');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_GRAVITYFORMS), 'notice-gravityforms'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_GRAVITYFORMS), 'notice-gravityforms-features'))->get_url();
    }

}
