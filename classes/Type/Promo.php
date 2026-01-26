<?php

namespace AC\Type;

use AC\Type;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use DateTime;

abstract class Promo
{

    protected string $slug;

    protected int $discount;

    protected DateRange $date_range;

    public function __construct(string $slug, int $discount, DateRange $date_range)
    {
        $this->slug = $slug;
        $this->discount = $discount;
        $this->date_range = $date_range;
    }

    abstract public function get_title(): string;

    abstract public function get_button_label(): string;

    abstract public function get_notice_message(): string;

    public function get_discount(): int
    {
        return $this->discount;
    }

    public function get_slug(): string
    {
        return $this->slug;
    }

    public function get_url(): Url
    {
        return (new UtmTags(new Site(Site::PAGE_PRICING), 'promo', null, $this->slug));
    }

    public function get_date_range(): Type\DateRange
    {
        return $this->date_range;
    }

    public function is_active(?DateTime $date = null): bool
    {
        return $this->date_range->in_range($date);
    }

}