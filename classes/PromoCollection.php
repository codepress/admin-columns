<?php

namespace AC;

use AC\Promo\BlackFriday;
use AC\Type\DateRange;
use DateTime;

class PromoCollection extends ArrayIterator
{

    public function __construct()
    {
        parent::__construct([
            new BlackFriday(new DateRange(new DateTime('2025-11-25'), new DateTime('2025-12-6')), 'BlackFriday2025'),
        ]);
    }

    /**
     * Returns the first active promotion it finds
     */
    public function find_active(): ?Promo
    {
        /**
         * @var Promo $promo
         */
        foreach ($this->array as $promo) {
            if ($promo->is_active()) {
                return $promo;
            }
        }

        return null;
    }

}