<?php

namespace AC;

use AC;
use AC\Type\DateRange;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

abstract class Promo
{

    private string $slug;

    private string $title;

    private int $discount;

    private DateRange $date_range;

    public function __construct(string $slug, string $title, int $discount, DateRange $date_range)
    {
        $this->slug = $slug;
        $this->title = $title;
        $this->discount = $discount;
        $this->date_range = $date_range;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function get_discount(): int
    {
        return $this->discount;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_url(): AC\Type\Url
    {
        return (new UtmTags(new Site(Site::PAGE_PRICING), 'promo', null, $this->slug));
    }

    public function get_date_range(): Type\DateRange
    {
        return $this->date_range;
    }

}