<?php

namespace AC\Type\Url;

use AC\Type;

class Site implements Type\Url
{

    use Path;

    public const URL = 'https://www.admincolumns.com';

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

    public function __construct(string $path = null)
    {
        if ($path) {
            $this->set_path($path);
        }
    }

    public function get_url(): string
    {
        return self::URL . $this->get_path();
    }

    public function __toString(): string
    {
        return $this->get_url();
    }

}