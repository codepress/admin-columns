<?php

namespace AC\View\Embed;

use AC\View;

class Video extends View
{

    public function __construct(array $attributes)
    {
        parent::__construct();

        $this->set('attributes', $attributes);
        $this->set_template('embed/video');
    }

    public function set_src(string $src)
    {
        return $this->set('src', $src);
    }

}