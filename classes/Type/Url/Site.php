<?php

namespace AC\Type\Url;

use AC\Type;

class Site extends Type\Uri
{

    public const URL = 'https://www.admincolumns.com';

    public const PAGE_CHANGELOG = '/changelog';
    public const PAGE_PRICING = '/pricing-purchase';
    public const PAGE_ABOUT_PRO = '/admin-columns-pro';
    public const PAGE_ACCOUNT_SUBSCRIPTIONS = '/my-account/subscriptions';
    public const PAGE_ADDONS = '/add-ons';
    public const PAGE_ADDON_ACF = '/advanced-custom-fields';
    public const PAGE_ADDON_BUDDYPRESS = '/buddypress';
    public const PAGE_ADDON_GRAVITYFORMS = '/gravity-forms';
    public const PAGE_ADDON_EVENTS_CALENDAR = '/events-calendar';
    public const PAGE_ADDON_METABOX = '/meta-box-integration';
    public const PAGE_ADDON_JETENGINE = '/jetengine';
    public const PAGE_ADDON_PODS = '/pods';
    public const PAGE_ADDON_TOOLSET_TYPES = '/toolset-types';
    public const PAGE_ADDON_WOOCOMMERCE = '/woocommerce-columns';
    public const PAGE_ADDON_YOAST_SEO = '/yoast-seo';
    public const PAGE_ADDON_RANK_MATH = '/rank-math';
    public const PAGE_ADDON_SEOPRESS = '/seo-press';
    public const PAGE_SUPPORT = '/documentation';

    public function __construct(?string $path = null)
    {
        parent::__construct(self::URL);

        if ($path) {
            $this->add_path($path);
        }
    }

    public static function create_pricing(): self
    {
        return new self(self::PAGE_PRICING);
    }

    public static function create_admin_columns_pro(): self
    {
        return new self(self::PAGE_ABOUT_PRO);
    }

    public static function create_support(): self
    {
        return new self(self::PAGE_SUPPORT);
    }

    public static function create_changelog(): self
    {
        return new self(self::PAGE_CHANGELOG);
    }

    public static function create_account(): self
    {
        return new self(self::PAGE_ACCOUNT_SUBSCRIPTIONS);
    }

}