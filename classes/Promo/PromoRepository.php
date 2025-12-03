<?php

declare(strict_types=1);

namespace AC\Promo;

use AC\Type\DateRange;
use AC\Type\Promo;
use AC\Type\PromoCollection;
use DateTime;

class PromoRepository
{

    public function find_all(): PromoCollection
    {
        return new PromoCollection([
            new BlackFriday(
                new DateRange(
                    new DateTime('2025-11-27'),
                    new DateTime('2025-12-04')
                ),
                'BlackFriday2025'
            ),
            new BlackFriday(
                new DateRange(
                    new DateTime('2026-11-26'),
                    new DateTime('2026-12-03')
                ),
                'BlackFriday26'
            ),
        ]);
    }

    public function find_active(): ?Promo
    {
        /**
         * @var Promo $promo
         */
        foreach ($this->find_all() as $promo) {
            if ($promo->is_active()) {
                return $promo;
            }
        }

        return null;
    }

}