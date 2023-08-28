<?php

namespace AC\Controller;

use AC\Admin\Preference\ScreenOptions;
use AC\Ajax;
use AC\Registerable;

class AjaxScreenOptions implements Registerable
{

    /**
     * @var ScreenOptions
     */
    private $preference;

    public function __construct(ScreenOptions $preference)
    {
        $this->preference = $preference;
    }

    public function register(): void
    {
        $this->get_ajax_handler()->register();
    }

    /**
     * @return Ajax\Handler
     */
    private function get_ajax_handler()
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_admin_screen_options')
            ->set_callback([$this, 'handle_ajax_request']);

        return $handler;
    }

    public function handle_ajax_request()
    {
        $this->get_ajax_handler()->verify_request();

        $name = filter_input(INPUT_POST, 'option_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $value = (int)filter_input(INPUT_POST, 'option_value', FILTER_SANITIZE_NUMBER_INT);

        $this->preference->set($name, $value);
    }

}