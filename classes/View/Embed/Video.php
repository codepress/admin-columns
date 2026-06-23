<?php

declare(strict_types=1);

namespace AC\View\Embed;

use AC\View;

class Video extends View
{
    public function __construct(array $attributes = [])
    {
        parent::__construct();

        $this->set('attributes', $attributes);
        $this->set_template('embed/video');
    }

    /**
     * @return static
     */
    public function set_src(string $src): self
    {
        $this->set('src', $src);

        return $this;
    }

}
