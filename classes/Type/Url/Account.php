<?php

declare(strict_types=1);

namespace AC\Type\Url;

use ACP\Type\ActivationToken;

class Account extends Site
{

    private ActivationToken $token;

    public function __construct(ActivationToken $token = null)
    {
        parent::__construct(Site::PAGE_ACCOUNT_SUBSCRIPTIONS);

        $this->token = $token;
    }

    public function get_url(): string
    {
        if ($this->token) {
            return add_query_arg(
                [
                    $this->token->get_type() => $this->token->get_token(),
                ],
                parent::get_url()
            );
        }

        return parent::get_url();
    }

}