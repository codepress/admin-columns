<?php

namespace AC;

class RequestAjaxParser implements Registerable
{

    private $handlers;

    public function __construct(RequestAjaxHandlers $handlers)
    {
        $this->handlers = $handlers;
    }

    public function register(): void
    {
        foreach ($this->handlers->all() as $action => $handler) {
            (new Ajax\Handler())->set_action($action)
                                ->set_callback([$handler, 'handle'])
                                ->register();
        }
    }

}