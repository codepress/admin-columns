<?php

namespace AC\Admin\Section;

use AC\Integration;
use AC\View;

class AddonStatus extends View
{

    public function __construct(Integration $integration)
    {
        parent::__construct([
            'url'   => (string)$integration->get_url(),
            'class' => '-pink',
        ]);

        $this->set_template('admin/page/component/addon/more-info');
    }

}