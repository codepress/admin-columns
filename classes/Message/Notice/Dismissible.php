<?php

namespace AC\Message\Notice;

use AC\Ajax\Handler;
use AC\Ajax\NullHandler;
use AC\Asset\Script;
use AC\Container;
use AC\Message\Notice;
use AC\View;

class Dismissible extends Notice
{

    protected Handler $handler;

    public function __construct(string $message, ?Handler $handler = null, ?string $type = null)
    {
        parent::__construct($message, $type);

        $this->handler = $handler ?? new NullHandler();
    }

    public function render(): string
    {
        $data = [
            'message'              => $this->message,
            'type'                 => $this->type,
            'id'                   => $this->id,
            'dismissible_callback' => $this->handler->get_params(),
        ];

        $view = new View($data);
        $view->set_template('message/notice/dismissible');

        return $view->render();
    }

    public function enqueue_scripts(): void
    {
        parent::enqueue_scripts();

        $script = new Script('ac-message', Container::get_location()->with_suffix('assets/js/notice-dismissible.js'));
        $script->enqueue();
    }

}