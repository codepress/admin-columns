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
            new BlackFriday(new DateRange(new DateTime('2022-11-25'), new DateTime('2022-11-30')), 'BlackFriday22'),
            new BlackFriday(new DateRange(new DateTime('2024-10-01'), new DateTime('2024-11-30')), 'BlackFriday24'),
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