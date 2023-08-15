<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;
use AC\Type\Url;

class Tweet implements QueryAware
{

    public const TWITTER_HANDLE = 'admincolumns';

    use QueryAwareTrait;

    public function __construct(string $text, Url $url, string $via, string $hastags)
    {
        $this->url = 'https://twitter.com/intent/tweet';
        $this->add_one('text', urlencode($text));
        $this->add_one('url', urlencode($url->get_url()));
        $this->add_one('via', $via);
        $this->add_one('hashtags', $hastags);
    }

}