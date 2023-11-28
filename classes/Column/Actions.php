<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Base class for columns containing action links for items.
 * @since 2.2.6
 */
class Actions extends Column
{

    /**
     * @since 2.2.6
     */
    public function __construct()
    {
        $this->set_type('column-actions');
        $this->set_label(__('Actions', 'codepress-admin-columns'));
    }

    /**
     * @param $id
     *
     * @return string
     * @since 2.2.6
     */
    public function get_value($id)
    {
        if ($this->get_setting('use_icons')->get_value()) {
            return '<span class="cpac_use_icons"></span>';
        }

        return '';
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\ActionIcons($this));
    }

    // TODO remove
    public function register_settings_temp()
    {
        $this->add_setting(new Settings\Column\ActionIcons($this));
    }

}