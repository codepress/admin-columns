<?php

namespace AC\Preferences;

use AC\Storage\UserMeta;

class UserFactory
{

    public const SCREEN_OPTIONS = 'admin_screen_options';

    public function create(string $key, ?int $user_id = null): Preference
    {
        return new Preference(
            new UserMeta('ac_preferences_' . $key, $user_id)
        );
    }

}