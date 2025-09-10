<?php

declare(strict_types=1);

namespace AC\Helper;

class Translations
{

    /**
     * Fetches remote translations. Expires in 7 days.
     */
    public function get_available_translations(): array
    {
        $translations = get_site_transient('ac_available_translations');

        if (false !== $translations) {
            return $translations;
        }

        require_once(ABSPATH . 'wp-admin/includes/translation-install.php');

        $translations = wp_get_available_translations();

        set_site_transient('ac_available_translations', wp_get_available_translations(), WEEK_IN_SECONDS);

        return $translations;
    }

}