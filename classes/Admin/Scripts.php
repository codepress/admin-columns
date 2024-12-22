<?php

namespace AC\Admin;

use AC\AdminColumns;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Registerable;

class Scripts implements Registerable
{

    private $location;

    public function __construct(AdminColumns $plugin)
    {
        $this->location = $plugin->get_location();
    }

    public function register(): void
    {
        add_action('init', [$this, 'register_scripts']);
    }

    public function register_scripts(): void
    {
        $assets = [
            new Script('ac-select2-core', $this->location->with_suffix('assets/js/select2.js')),
            new Script(
                'ac-select2',
                $this->location->with_suffix('assets/js/select2_conflict_fix.js'),
                ['jquery', 'ac-select2-core']
            ),
            new Style('ac-select2', $this->location->with_suffix('assets/css/select2.css')),
            new Style('ac-jquery-ui', $this->location->with_suffix('assets/css/ac-jquery-ui.css')),
        ];

        foreach ($assets as $asset) {
            $asset->register();
        }
    }

}