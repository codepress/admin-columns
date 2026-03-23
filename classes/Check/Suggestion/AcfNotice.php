<?php

declare(strict_types=1);

namespace AC\Check\Suggestion;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class AcfNotice implements SuggestionNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('acf', false) && ! class_exists('ACF', false)) {
            return false;
        }

        return in_array($screen->get_id(), [
            'edit-acf-field-group',
            'acf-field-group',
        ], true);
    }

    public function get_slug(): string
    {
        return 'acf';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-acf';
    }

    public function get_icon(): string
    {
        return '⚡';
    }

    public function get_eyebrow(): string
    {
        return __('Admin Columns Pro for ACF', 'codepress-admin-columns');
    }

    public function get_title(): string
    {
        return __('Your ACF fields could be visible in every list table!', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Turn custom fields into sortable, filterable, editable columns - without writing a single line of code.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return sprintf('%s - %s', __('Upgrade', 'codepress-admin-columns'), '€79/year');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-features'))->get_url();
    }

}
