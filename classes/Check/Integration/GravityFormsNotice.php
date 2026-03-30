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

    public function get_eyebrow(): string
    {
        return '';
    }

    public function get_title(): string
    {
        return __('Still opening entries to edit or manage data?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Edit fields inline, filter and search faster, and export your entries - all without leaving this table.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
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

    public function get_extra_classes(): string
    {
        return 'gf-notice';
    }

    public function get_delay_days(): int
    {
        return 14;
    }

}
