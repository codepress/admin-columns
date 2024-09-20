<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\RequestFactory;

final class DismissHandler implements RequestAjaxHandler
{

    private Repository $repository;

    private Request $request;

    private Nonce\Ajax $nonce;

    public function __construct(
        Repository $repository,
        RequestFactory $request_factory,
        Nonce\Ajax $nonce
        // TODO Tobias add action handler to deal with the action id
    ) {
        $this->repository = $repository;
        $this->request = $request_factory->create();
        $this->nonce = $nonce;
    }

    public function handle(): void
    {
        if ($this->nonce->verify($this->request)) {
            return;
        }

        $id = $this->request->filter('notice_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $notice = $this->repository->find($id);

        if ($notice && $notice->has_action_id()) {
            // let the process id handler do something with it...
        }

        $this->repository->delete($id);

        wp_send_json_success();
    }

}