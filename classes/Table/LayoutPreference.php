<?php

namespace AC\Table;

use AC\Preferences\Preference;
use AC\Storage\UserOption;

class LayoutPreference extends Preference
{

    public function __construct()
    {
        parent::__construct(new UserOption('layout_table'));
    }

}