<?php

declare(strict_types=1);

namespace AC\Plugin\Install;

use AC;
use WP_Roles;

final class Capabilities implements AC\Plugin\Install
{

    public function install(): void
    {
        global $wp_roles;

        if ( ! $wp_roles) {
            $wp_roles = new WP_Roles();
        }

        do_action('ac/capabilities/init', $wp_roles);
    }

}