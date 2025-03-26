<?php

declare(strict_types=1);

namespace AC\RequestHandler\Ajax;

use AC\Capabilities;
use AC\ListScreen;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use AC\Response\Json;
use AC\Response\JsonListScreenSettingsFactory;
use AC\Type\ListScreenId;
use ACP\ListScreenRepository\TemplateStorageFactory;
use InvalidArgumentException;

class ListScreenTemplateSettings implements RequestAjaxHandler
{

    private TemplateStorageFactory $storage_factory;

    private JsonListScreenSettingsFactory $response_factory;

    /**
     *
     */
    public function __construct(
        TemplateStorageFactory $storage_factory,
        JsonListScreenSettingsFactory $response_factory
    ) {
        $this->storage_factory = $storage_factory;
        $this->response_factory = $response_factory;
    }

    public function handle(): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        $request = new Request();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            (new Json())->error();
        }

        $storage = $this->storage_factory->create();

        $template = $storage->find(
            new ListScreenId($request->get('list_id'))
        );

        if ( ! $template instanceof ListScreen) {
            throw new InvalidArgumentException('Invalid template.');
        }

        $this->response_factory->create($template)
                               ->success();
    }

}