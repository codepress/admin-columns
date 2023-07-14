<?php

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;
use AC\Type\Url;

class CouponCode implements QueryAware
{

    use QueryAwareTrait;

    private const ARG_COUPON = 'coupon_code';

    public function __construct(Url $url, string $coupon_code)
    {
        $this->url = $url->get_url();

        $this->add_one(self::ARG_COUPON, $coupon_code);
    }

    public function add_coupon_code(string $coupon_code): CouponCode
    {
        $this->add_one(self::ARG_COUPON, $coupon_code);

        return $this;
    }

}