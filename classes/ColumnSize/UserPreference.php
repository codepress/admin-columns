<?php

namespace AC\ColumnSize;

use AC\Preferences\Preference;
use AC\Storage\UserOption;

class UserPreference extends Preference
{

    public function __construct(int $user_id = null)
    {
        parent::__construct(new UserOption('column_widths', $user_id));
    }

}