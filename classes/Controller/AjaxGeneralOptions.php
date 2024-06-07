<?php

namespace AC\Controller;

use AC\Ajax;
use AC\Capabilities;
use AC\Registerable;
use AC\Request;
use AC\Storage\GeneralOption;

class AjaxGeneralOptions implements Registerable
{

    private $storage;

    public function __construct(GeneralOption $storage)
    {
        $this->storage = $storage;
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
            exit;
        }

        $request = new Request();

        $name = (string)$request->filter('option_name');
        $value = (string)$request->filter('option_value');

        $this->storage->save($name, $value);

        exit;
    }

}