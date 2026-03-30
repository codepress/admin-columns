<?php

namespace AC\Service;

use AC\Acf\FieldCount;
use AC\AdminColumns;
use AC\Capabilities;
use AC\Check;
use AC\Check\Integration;
use AC\Notice\NoticeState;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private AdminColumns $plugin;

    private FieldCount $field_count;

    public function __construct(AdminColumns $plugin, FieldCount $field_count)
    {
        $this->plugin = $plugin;
        $this->field_count = $field_count;
    }

    public function register(): void
    {
        $this->create_services()->register();
    }

    private function create_services(): Services
    {
        $services = new Services();
        $services->add($this->field_count);

        if (current_user_can(Capabilities::MANAGE)) {
            $state = new NoticeState();

            $services->add(new Check\Review($this->plugin->get_location(), $state));

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
                        new Integration\AcfBulkEditNotice($this->field_count),
                        new Integration\AcfSortAndFilterNotice($this->field_count),
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