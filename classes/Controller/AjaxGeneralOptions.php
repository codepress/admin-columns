<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Capabilities;
use AC\Registerable;
use AC\Request;
use AC\Settings\GeneralOptionFactory;

class AjaxGeneralOptions implements Registerable
{

    private $option_factory;

    public function __construct(GeneralOptionFactory $option_factory)
    {
        $this->option_factory = $option_factory;
    }

    public function register(): void
    {
        $this->get_ajax_handler()->register();
    }

    private function get_ajax_handler(): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_admin_general_options')
            ->set_callback([$this, 'handle_request']);

        return $handler;
    }

    public function handle_request()
    {
        $this->get_ajax_handler()->verify_request();

        if ( ! current_user_can(Capabilities::MANAGE)) {
            wp_send_json_error();
        }

        $request = new Request();

        $name = (string)$request->filter('option_name');
        $value = (string)$request->filter('option_value');

        $this->option_factory->create()
                             ->save($name, $value);

        wp_send_json_success();
    }

}