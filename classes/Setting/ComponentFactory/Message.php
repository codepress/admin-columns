<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;

class Message extends Builder
{

    private $label;

    private $message;

    public function __construct(string $label, string $message)
    {
        $this->label = $label;
        $this->message = $message;
    }

    protected function get_label(Config $config): ?string
    {
        return $this->label;
    }

    //TODO
    protected function get_input(Config $config): ?Input
    {
        return new Input\Custom('message', null, [
            'message' => $this->message,
        ]);
    }
}