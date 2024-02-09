<?php

namespace AC\Column;

use AC\Column;
use AC\Setting\Config;
use AC\Setting\ComponentCollection;
use AC\Settings;

class Actions extends Column implements Renderable
{

    public function __construct()
    {
        parent::__construct(
            'column-actions',
            __('Actions', 'codepress-admin-columns'),
            new ComponentCollection([
                new Settings\Column\ActionIcons(),
            ])
        );
    }

    public function render($id, Config $options = null): string
    {
        // todo
    }



    // TODO remove

    //    public function get_value($id)
    //    {
    //        if ($this->get_option('use_icons')) {
    //            return '<span class="cpac_use_icons"></span>';
    //        }
    //
    //        return '';
    //    }
    //
    //    public function register_settings()
    //    {
    //        $this->add_setting(new Settings\Column\ActionIcons());
    //    }

}