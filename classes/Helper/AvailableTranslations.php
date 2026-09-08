<?php

declare(strict_types=1);

namespace AC\Helper;

class AvailableTranslations extends Creatable
{
    private const TRANSIENT_KEY = 'ac_available_translations';

    /**
     * @var array|null
     */
    private static $translations;

    public function get_available_translations(): array
    {
        if (null !== self::$translations) {
            return self::$translations;
        }

        $translations = get_site_transient(self::TRANSIENT_KEY);

        if (false === $translations) {
            require_once(ABSPATH . 'wp-admin/includes/translation-install.php');

            $translations = wp_get_available_translations();

            // A failed fetch is cached too, for a shorter period. Without it an unreachable
            // api.wordpress.org makes every caller wait for its own timeout.
            set_site_transient(
                self::TRANSIENT_KEY,
                $translations,
                $translations ? WEEK_IN_SECONDS : HOUR_IN_SECONDS
            );
        }

        self::$translations = (array)$translations;

        return self::$translations;
    }

}
