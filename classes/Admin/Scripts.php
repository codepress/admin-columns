<?php

namespace AC\Admin;

use AC\Asset\Location\Absolute;
use AC\Asset\Style;
use AC\Registerable;

class Scripts implements Registerable
{

    private $location;

    public function __construct(Absolute $location)
    {
        $this->location = $location;
    }

    public function register(): void
    {
        add_action('init', [$this, 'register_scripts']);
    }

    public function register_scripts(): void
    {
        $assets = [
            // TODO check if this is still used
            new Style('ac-jquery-ui', $this->location->with_suffix('assets/css/ac-jquery-ui.css')),
        ];

        foreach ($assets as $asset) {
            $asset->register();
        }
    }

}