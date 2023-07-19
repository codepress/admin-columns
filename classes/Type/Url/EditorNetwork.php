<?php

namespace AC\Type\Url;

use AC\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type\Uri;

class EditorNetwork extends Uri
{

    public function __construct($slug = null)
    {
        parent::__construct(network_admin_url('settings.php'));

        $this->add_arg(RequestHandlerInterface::PARAM_PAGE, Admin\Admin::NAME);

        if ($slug) {
            $this->add_arg(RequestHandlerInterface::PARAM_TAB, $slug);
        }
    }

}