<?php

namespace AC\Admin;

use AC\AdminColumns;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;

class AdminScripts implements Enqueueables
{

    private Location $location;

    public function __construct(AdminColumns $plugin)
    {
        $this->location = $plugin->get_location();
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Style(
                'ac-admin',
                $this->location->with_suffix('assets/css/admin-general.css'),
                ['ac-ui', 'ac-utilities']
            ),
        ]);
    }

}