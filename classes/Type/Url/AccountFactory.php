<?php

declare(strict_types=1);

namespace AC\Type\Url;

use ACP\ActivationTokenFactory;
use ACP\Type\SiteUrl;

class AccountFactory
{

    private SiteUrl $site_url;

    private ActivationTokenFactory $token_factory;

    public function __construct(SiteUrl $site_url, ActivationTokenFactory $token_factory)
    {
        $this->site_url = $site_url;
        $this->token_factory = $token_factory;
    }

    public function create(): account
    {
        return new Account(
            $this->site_url,
            $this->token_factory->create()
        );
    }

}