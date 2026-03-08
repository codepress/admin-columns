<?php

declare(strict_types=1);

namespace AC\Service;

use AC;
use AC\Check\Promotion;
use AC\Notice\DismissRegistry;

class PromoChecks implements AC\Registerable
{

    private AC\Promo\PromoRepository $repository;

    private AC\Preferences\UserFactory $preference_factory;

    private DismissRegistry $dismiss_registry;

    public function __construct(AC\Promo\PromoRepository $repository, AC\Preferences\UserFactory $preference_factory, DismissRegistry $dismiss_registry)
    {
        $this->repository = $repository;
        $this->preference_factory = $preference_factory;
        $this->dismiss_registry = $dismiss_registry;
    }

    public function register(): void
    {
        $promo = $this->repository->find_active();

        if ( ! $promo) {
            return;
        }

        $service = new Promotion($promo, $this->preference_factory, $this->dismiss_registry);
        $service->register();
    }

}