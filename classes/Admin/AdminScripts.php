<?php

namespace AC\Admin;

use AC\AdminColumns;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Script;
use AC\Asset\Style;

class AdminScripts implements Enqueueables
{

    private $location;

    public function __construct(AdminColumns $plugin)
    {
        $this->location = $plugin->get_location();
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Script('ac-admin-general', $this->location->with_suffix('assets/js/admin-general.js'), ['jquery']),
            new Style(
                'ac-admin',
                $this->location->with_suffix('assets/css/admin-general.css'),
                ['ac-ui', 'ac-utilities']
            ),
        ]);
    }

}