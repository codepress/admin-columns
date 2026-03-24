<?php

declare(strict_types=1);

namespace AC\Service;

use AC\AdminColumns;
use AC\Capabilities;
use AC\Check;
use AC\Check\Integration;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private AdminColumns $plugin;

    public function __construct(AdminColumns $plugin)
    {
        $this->plugin = $plugin;
    }

    public function register(): void
    {
        $this->create_services()->register();
    }

    private function create_services(): Services
    {
        $services = new Services();

        if (current_user_can(Capabilities::MANAGE)) {
            $services->add(new Check\Review($this->plugin->get_location()));

            $services->add(
                new Integration\IntegrationNoticeRenderer(
                    [
                        new Integration\WooCommerceProductsNotice(),
                        new Integration\WooCommerceOrdersNotice(),
                        new Integration\AcfNotice(),
                        new Integration\GravityFormsNotice(),
                        new Integration\EventsCalendarNotice(),
                    ],
                )
            );
        }

        return $services;
    }

}