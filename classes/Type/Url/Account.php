<?php

declare(strict_types=1);

namespace AC\Type\Url;

use ACP\Type\ActivationToken;
use ACP\Type\SiteUrl;

class Account extends Site
{

    private ActivationToken $token;

    private SiteUrl $site_url;

    public function __construct(SiteUrl $site_url, ActivationToken $token = null)
    {
        parent::__construct(Site::PAGE_ACCOUNT_SUBSCRIPTIONS);

        $this->token = $token;
        $this->site_url = $site_url;
    }

    public function get_url(): string
    {
        if ($this->token) {
            return add_query_arg(
                [
                    $this->token->get_type() => $this->token->get_token(),
                    'site_url'               => (string)$this->site_url,
                ],
                parent::get_url()
            );
        }

        return parent::get_url();
    }

}