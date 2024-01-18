<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Base class for columns containing action links for items.
 */
class Actions extends Column
{

    public function __construct()
    {
        $this->set_type('column-actions');
        $this->set_label(__('Actions', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        if ($this->get_option('use_icons')) {
            return '<span class="cpac_use_icons"></span>';
        }

        return '';
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\ActionIcons());
    }

}