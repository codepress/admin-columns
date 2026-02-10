<?php

namespace AC\Admin;

use AC\Request;

class PageRequestHandlers implements RequestHandlerInterface
{

    /**
     * @var RequestHandlerInterface[]
     */
    public static array $handlers = [];

    public static function add_handler(RequestHandlerInterface $handler): void
    {
        self::$handlers[] = $handler;
    }

    public function handle(Request $request)
    {
        $page = null;

        foreach (array_reverse(self::$handlers) as $handler) {
            $page = $handler->handle($request);

            if ($page) {
                break;
            }
        }

        return $page;
    }

}