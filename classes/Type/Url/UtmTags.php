<?php

namespace AC\Type\Url;

use AC\Type\Uri;
use AC\Type\Url;

class UtmTags extends Uri
{

    public const ARG_SOURCE = 'utm_source';
    public const ARG_MEDIUM = 'utm_medium';
    public const ARG_CONTENT = 'utm_content';
    public const ARG_CAMPAIGN = 'utm_campaign';

    public function __construct(Url $url, string $medium = null, string $content = null, string $campaign = null)
    {
        parent::__construct($url->get_url());

        $this->add(self::ARG_SOURCE, 'plugin-installation');

        if ($medium) {
            $this->add(self::ARG_MEDIUM, $medium);
        }

        if ($content) {
            $this->add(self::ARG_CONTENT, $content);
        }

        if ($campaign) {
            $this->add(self::ARG_CAMPAIGN, $campaign);
        }
    }

    public function add_medium(string $medium): self
    {
        return new self($this, $medium);
    }

    public function add_content(string $content): self
    {
        return new self($this, null, $content);
    }

}