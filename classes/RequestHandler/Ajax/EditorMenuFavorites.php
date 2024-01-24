<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Storage;
use AC\Type\ListKey;

class EditorMenuFavorites implements RequestAjaxHandler
{

    private $favorite_repository;

    public function __construct(Storage\Repository\EditorFavorites $favorite_repository)
    {
        $this->favorite_repository = $favorite_repository;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_key = new ListKey($request->get('list_key'));

        'favorite' === $request->get('status')
            ? $this->favorite_repository->add($list_key)
            : $this->favorite_repository->remove($list_key);

        $response->success();
    }

}