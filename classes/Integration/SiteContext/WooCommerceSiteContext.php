<?php

declare(strict_types=1);

namespace AC\Integration\SiteContext;

class WooCommerceSiteContext
{

    public function get_context(): ?array
    {
        if ( ! function_exists('wp_count_posts')) {
            return null;
        }

        $counts = wp_count_posts('product');

        if ((int)$counts->publish === 0) {
            return null;
        }

        return [
            'product_count' => (int)$counts->publish,
        ];
    }

}
