<?php

namespace AC;

class RequestAjaxHandlers
{

    /**
     * @var RequestAjaxHandler[]
     */
    private $request_handlers = [];

    public function add(string $action, RequestAjaxHandler $request_handler): self
    {
        $this->request_handlers[$action] = $request_handler;

        return $this;
    }

    /**
     * @return RequestAjaxHandler[]
     */
    public function all(): array
    {
        return $this->request_handlers;
    }

}