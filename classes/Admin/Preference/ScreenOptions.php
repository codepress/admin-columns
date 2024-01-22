<?php

namespace AC\Admin\Preference;

use AC\Preferences\Preference;
use AC\Storage\UserMeta;

class ScreenOptions extends Preference
{

    public function __construct()
    {
        parent::__construct(new UserMeta('admin_screen_options'));
    }

}