<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Acf\FieldGroupCache;
use AC\Capabilities;
use AC\Check;
use AC\Check\Integration;
use AC\Notice\NoticeState;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{
    private FieldGroupCache $field_group_cache;

    public function __construct(FieldGroupCache $field_group_cache)
    {
        $this->field_group_cache = $field_group_cache;
    }

    public function register(): void
    {
        $this->create_services()->register();
    }

    private function create_services(): Services
    {
        $services = new Services();

        if (current_user_can(Capabilities::MANAGE)) {
            $state = new NoticeState();

            $services->add(new Check\Review($state));

            $services->add(
                new Integration\IntegrationNoticeRenderer(
                    [
                        new Integration\WooCommerceProductsBulkEditNotice(),
                        new Integration\WooCommerceProductsSearchNotice(),
                        new Integration\WooCommerceProductsFilterNotice(),
                        new Integration\WooCommerceProductsNotice(),
                        new Integration\WooCommerceOrdersSearchNotice(),
                        new Integration\WooCommerceOrdersFilterNotice(),
                        new Integration\WooCommerceOrdersNotice(),
                        new Integration\AcfBulkEditNotice($this->field_group_cache),
                        new Integration\AcfSortAndFilterNotice($this->field_group_cache),
                        new Integration\AcfNotice(),
                        new Integration\GravityFormsNotice(),
                        new Integration\EventsCalendarNotice(),
                    ],
                    $state
                )
            );
        }

        return $services;
    }

}
