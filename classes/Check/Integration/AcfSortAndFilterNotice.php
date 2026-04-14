<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Acf;
use AC\Acf\FieldGroupCache;
use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class AcfSortAndFilterNotice implements IntegrationNotice, UsageAwareNotice
{

    private FieldGroupCache $field_group_cache;

    public function __construct(FieldGroupCache $field_group_cache)
    {
        $this->field_group_cache = $field_group_cache;
    }

    public function is_active(Screen $screen): bool
    {
        if ( ! Acf::is_active()) {
            return false;
        }

        if ('edit' !== $screen->get_base() || empty($screen->get_post_type())) {
            return false;
        }

        // Restrict the notice to post types that have ACF fields
        return $this->field_group_cache->get_count_for_post_type($screen->get_post_type()) >= 2;
    }

    public function is_usage_detected(): bool
    {
        return ! empty($_GET['orderby']) || ! empty($_GET['s']);
    }

    public function get_slug(): string
    {
        return 'acf-sort-filter';
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
        return __('Sort and filter by your custom fields', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('With Pro, sort by date pickers, numbers, and choice fields. Filter by any ACF field type. Save filters as reusable segments and switch between them in one click.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-sort-filter'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-sort-filter-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function get_delay_days(): int
    {
        return 14;
    }

}
