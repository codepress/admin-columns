<?php

namespace AC\Admin;

use AC\Renderable;

interface PageFactoryInterface
{

    /**
     * @return Renderable
     */
    public function create();

}