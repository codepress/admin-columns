<?php

namespace AC\Type\Url;

use AC\Type\Uri;
use AC\Type\Url;

class UtmTags extends Uri
{

    public function __construct(Url $url, ?string $medium = null, ?string $content = null, ?string $campaign = null)
    {
        parent::__construct($url->get_url());

        $this->add('utm_source', 'plugin-installation');

        if ($medium) {
            $this->add('utm_medium', $medium);
        }

        if ($content) {
            $this->add('utm_content', $content);
        }

        if ($campaign) {
            $this->add('utm_campaign', $campaign);
        }
    }

    public function with_content(string $content): self
    {
        return new self($this, null, $content);
    }

}