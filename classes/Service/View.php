<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\Registerable;

class View implements Registerable
{

    private Absolute $location;

    public function __construct(Absolute $location)
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