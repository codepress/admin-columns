<?php

namespace AC\ColumnSize;

use AC\Preferences\Site;

class UserPreference extends Site
{

    public function __construct(int $user_id = null)
    {
        parent::__construct('column_widths', $user_id);
    }

}