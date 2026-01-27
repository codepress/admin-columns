<?php

namespace AC\Type\Url;

use AC\Type\Uri;
use AC\Type\Url;

class CouponCode extends Uri
{

    public function __construct(Url $url, string $coupon_code)
    {
        parent::__construct($url->get_url());

        $this->add('coupon_code', $coupon_code);
    }

}