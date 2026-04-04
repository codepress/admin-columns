<?php

declare(strict_types=1);

namespace AC\Admin;

use AC\Request;

interface RequestHandlerInterface
{

    public const PARAM_PAGE = 'page';
    public const PARAM_TAB = 'tab';

    public function handle(Request $request);

}