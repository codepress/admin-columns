<?php

namespace AC\Admin;

use AC\Request;

class PageNetworkRequestHandler implements RequestHandlerInterface
{

    /**
     * @var PageFactoryInterface[]
     */
    private array $factories = [];

    public function add(string $slug, PageFactoryInterface $factory): self
    {
        $this->factories[$slug] = $factory;

        return $this;
    }

    public function handle(Request $request)
    {
        $slug = $request->get_query()->get(self::PARAM_TAB) ?: 'columns';

        $page = isset($this->factories[$slug])
            ? $this->factories[$slug]->create()
            : null;

        return apply_filters('ac/admin/network/request/page', $page, $request);
    }

}