<?php

namespace AC;

use LogicException;

class RequestHandlerFactory
{

    /**
     * @var RequestHandler[]
     */
    private $request_handlers;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function add(string $action, RequestHandler $request_handler): self
    {
        $this->request_handlers[$action] = $request_handler;

        return $this;
    }

    public function is_request(): bool
    {
        return null !== $this->get_request_handler();
    }

    private function get_request_handler(): ?RequestHandler
    {
        $action = $this->request->get('action') ?: $this->request->get('ac_action');

        return $this->request_handlers[$action] ?? null;
    }

    public function create(): RequestHandler
    {
        if ( ! $this->is_request()) {
            throw new LogicException('Invalid request.');
        }

        return $this->get_request_handler();
    }

}