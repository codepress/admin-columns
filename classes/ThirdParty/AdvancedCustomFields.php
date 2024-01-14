<?php

declare(strict_types=1);

namespace AC\ThirdParty;

use AC\Registerable;

class AdvancedCustomFields implements Registerable
{

    public function register(): void
    {
        if ( ! class_exists('acf', false)) {
            return;
        }

        add_filter('ac/post_types', static function (array $post_types) {
            unset($post_types['acf-post-type']);
            unset($post_types['acf-taxonomy']);

            return $post_types;
        });
    }

}