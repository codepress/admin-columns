<?php

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;
use AC\Type\Url;

class UtmTags implements QueryAware
{

    use QueryAwareTrait;

    public const ARG_SOURCE = 'utm_source';
    public const ARG_MEDIUM = 'utm_medium';
    public const ARG_CONTENT = 'utm_content';
    public const ARG_CAMPAIGN = 'utm_campaign';

    public function __construct(Url $url, string $medium, string $content = null, string $campaign = null)
    {
        $this->url = $url->get_url();
        $this->add([
            self::ARG_SOURCE => 'plugin-installation',
            self::ARG_MEDIUM => $medium,
        ]);

        if ($content) {
            $this->add_content($content);
        }

        if ($campaign) {
            $this->add_campaign($campaign);
        }
    }

    public function add_medium(string $medium): self
    {
        $this->add_one(self::ARG_MEDIUM, $medium);

        return $this;
    }

    public function add_content(string $content): self
    {
        $this->add_one(self::ARG_CONTENT, $content);

        return $this;
    }

    public function add_campaign(string $campaign): self
    {
        $this->add_one(self::ARG_CAMPAIGN, $campaign);

        return $this;
    }

}