<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ListScreenRepository\Storage;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response;
use AC\Type\ListScreenId;

class ListScreenDelete implements RequestAjaxHandler
{

    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();
        $response = new Response\Json();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            $response->error();
        }

        $list_screen = $this->storage->find(new ListScreenId($request->get('list_id')));

        if ( ! $list_screen) {
            $response->error();
        }

        $this->storage->delete($list_screen);

        do_action('acp/list_screen/deleted', $list_screen);

        $response->set_message(
            sprintf(
                __('Table view %s successfully deleted.', 'codepress-admin-columns'),
                sprintf('<strong>"%s"</strong>', esc_html($list_screen->get_title() ?: $list_screen->get_label()))
            )
        )->success();
    }

}