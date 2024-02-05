<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

// TODO
abstract class Pro extends Settings\Setting
{

    /**
     * @return string
     */
    abstract protected function get_label();

    /**
     * @return View
     */
    abstract protected function get_instructions();

    public function create_view()
    {
        $setting = $this->create_element('radio')
                        ->set_options([
                            'on'  => __('Yes'),
                            'off' => __('No'),
                        ])
                        ->set_value('off');

        $view = new View();
        $view->set('label', $this->get_label())
             ->set('instructions', $this->get_instructions()->render())
             ->set('setting', $setting)
             ->set_template('settings/setting-pro');

        return $view;
    }

}