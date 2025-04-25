<?php

namespace AC\ThirdParty;

use AC\Registerable;

class WooCommerce implements Registerable
{

    public function register(): void
    {
        add_filter('ac/post_types', [$this, 'remove_webhook']);
    }

    public function remove_webhook($post_types)
    {
        if (class_exists('WooCommerce', false)) {
            if (isset($post_types['shop_webhook'])) {
                unset($post_types['shop_webhook']);
            }
        }

        return $post_types;
    }

}