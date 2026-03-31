<?php

declare(strict_types=1);

namespace AC\Integration\SiteContext;

class SiteContextFactory
{

    public function create(string $slug): ?array
    {
        switch ($slug) {
            case 'ac-addon-acf':
                return (new AcfSiteContext())->get_context();
            case 'ac-addon-woocommerce':
                return (new WooCommerceSiteContext())->get_context();
            default:
                return null;
        }
    }

}
