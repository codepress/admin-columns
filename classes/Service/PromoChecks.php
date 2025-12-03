<?php

declare(strict_types=1);

namespace AC\Service;

use AC;
use AC\Check\Promotion;

class PromoChecks implements AC\Registerable
{

    private AC\Promo\PromoRepository $repository;

    private AC\Preferences\UserFactory $preference_factory;

    public function __construct(AC\Promo\PromoRepository $repository, AC\Preferences\UserFactory $preference_factory)
    {
        $this->repository = $repository;
        $this->preference_factory = $preference_factory;
    }

    public function register(): void
    {
        $promo = $this->repository->find_active();

        if ( ! $promo) {
            return;
        }

        $service = new Promotion($promo, $this->preference_factory);
        $service->register();
    }

}