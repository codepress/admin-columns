<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Acf;
use AC\Acf\FieldGroupCache;
use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class AcfBulkEditNotice implements IntegrationNotice, UsageAwareNotice
{

    use PostEditReferrerAware;

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
        return $this->is_post_edit_referrer();
    }

    public function get_slug(): string
    {
        return 'acf-bulk-edit';
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
        return __('Editing custom fields one post at a time?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('With Pro, edit ACF fields directly from this screen - text, dates, images, relationships, and more. Update multiple posts without opening each one.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-bulk-edit'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'notice-acf-bulk-edit-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function get_delay_days(): int
    {
        return 28;
    }

}
