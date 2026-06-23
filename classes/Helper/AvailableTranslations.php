<?php

declare(strict_types=1);

namespace AC\Helper;

class AvailableTranslations extends Creatable
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

        if (! empty($translations)) {
            set_site_transient('ac_available_translations', $translations, WEEK_IN_SECONDS);
        }

        return $translations;
    }

}
