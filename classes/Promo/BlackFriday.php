<?php

namespace AC\Promo;

use AC\Type\DateRange;
use AC\Type\Promo;
use AC\Type\Url;
use AC\Type\Url\CouponCode;

final class BlackFriday extends Promo
{

    private string $coupon_code;

    public function __construct(DateRange $date_range, ?string $coupon_code = null)
    {
        parent::__construct('black-friday', 25, $date_range);

        $this->coupon_code = $coupon_code;
    }

    public function get_title(): string
    {
        return sprintf(
            __('Save up to %s from Black Friday to Cyber Monday', 'codepress-admin-columns'),
            $this->discount . '%'
        );
    }

    public function get_button_label(): string
    {
        return sprintf(
            __('Get up to %s Off!', 'codepress-admin-columns'),
            $this->discount . '%'
        );
    }

    public function get_notice_message(): string
    {
        return sprintf(
            '%s! <a target="_blank" href="%s">%s</a>',
            $this->get_title(),
            $this->get_url()->get_url(),
            sprintf(__('Get %s now', 'codepress-admin-columns'), '<strong>Admin Columns Pro</strong>')
        );
    }

    public function get_url(): Url
    {
        return $this->coupon_code
            ? new CouponCode(parent::get_url(), $this->coupon_code)
            : parent::get_url();
    }

}