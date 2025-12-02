<?php

namespace AC\Service;

use AC\Check\Promotion;
use AC\PromoCollection;
use AC\Registerable;

class PromoChecks implements Registerable
{

    private $promos;

    public function __construct(PromoCollection $promos)
    {
        $this->promos = $promos;
    }

    public function register(): void
    {
        $promo = $this->promos->find_active();

        if ($promo) {
            $service = new Promotion($promo);
            $service->register();
        }
    }

}