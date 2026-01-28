<?php

namespace AC\Type\Url;

use AC\Type\Uri;
use AC\Type\Url;

class UtmTags extends Uri
{

    private ?string $medium;

    private ?string $content;

    private ?string $campaign;

    public function __construct(Url $url, ?string $medium = null, ?string $content = null, ?string $campaign = null)
    {
        parent::__construct((string)$url);

        $this->add('utm_source', 'plugin-installation');

        $this->medium = $medium;
        $this->content = $content;
        $this->campaign = $campaign;
    }

    public function get_url(): string
    {
        $url = parent::get_url();

        if ($this->medium) {
            $url = add_query_arg('utm_medium', $this->medium, $url);
        }
        if ($this->content) {
            $url = add_query_arg('utm_content', $this->content, $url);
        }
        if ($this->campaign) {
            $url = add_query_arg('utm_campaign', $this->campaign, $url);
        }

        return $url;
    }

    public function with_content(string $content): self
    {
        return new self($this, $this->medium, $content, $this->campaign);
    }

}