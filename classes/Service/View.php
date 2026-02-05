<?php

namespace AC\Service;

use AC\Asset\Location;
use AC\Registerable;

class View implements Registerable
{

    private Location $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function register(): void
    {
        add_filter('ac/view/templates', [$this, 'templates']);
    }

    public function templates(array $templates): array
    {
        $templates[] = $this->location->with_suffix('templates')->get_path();

        return $templates;
    }

}