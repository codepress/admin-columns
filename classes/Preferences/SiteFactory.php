<?php

namespace AC\Preferences;

use AC\Storage\UserOption;

class SiteFactory
{

    public function create(string $key, ?int $user_id = null): Preference
    {
        return new Preference(
            new UserOption('ac_preferences_' . $key, $user_id)
        );
    }

}