<?php

namespace AC\Type\Url;

use AC\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type\Uri;

class Editor extends Uri
{

    public function __construct(?string $slug = null)
    {
        parent::__construct(admin_url('options-general.php'));

        $this->add(RequestHandlerInterface::PARAM_PAGE, Admin\Admin::NAME);

        if ($slug) {
            $this->add(RequestHandlerInterface::PARAM_TAB, $slug);
        }
    }

}