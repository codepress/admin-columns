<?php

namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\View;

class ProCta extends Section
{

    public function __construct()
    {
        parent::__construct('pro-cta');
    }

    public function render(): string
    {
        $view = new View([
            'title' => 'Admin Columns Pro',
            'description' => __(
                'Upgrade to Admin Columns Pro and unlock all the awesome features.',
                'codepress-admin-columns'
            ),
        ]);

        $view->set_template('admin/page/settings-section-pro-cta');

        return $view->render();
    }

}