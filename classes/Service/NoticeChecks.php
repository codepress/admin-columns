<?php

namespace AC\Service;

use AC\AdminColumns;
use AC\Capabilities;
use AC\Check;
use AC\Check\Suggestion;
use AC\Integration\IntegrationRepository;
use AC\Registerable;
use AC\Services;

class NoticeChecks implements Registerable
{

    private IntegrationRepository $integration_repository;

    private AdminColumns $plugin;

    public function __construct(AdminColumns $plugin, IntegrationRepository $integration_repository)
    {
        $this->plugin = $plugin;
        $this->integration_repository = $integration_repository;
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
                new Suggestion\SuggestionNoticeRenderer(
                    array_merge(
                        [
                            new Suggestion\WooCommerceProductsNotice(),
                            new Suggestion\WooCommerceOrdersNotice(),
                            new Suggestion\AcfNotice(),
                        ],
                        $this->create_integration_notices()
                    )
                )
            );
        }

        return $services;
    }

    /**
     * @return Suggestion\IntegrationNotice[]
     */
    private function create_integration_notices(): array
    {
        $icons = [
            'ac-addon-buddypress'      => '👥',
            'ac-addon-events-calendar' => '📅',
            'ac-addon-gravityforms'    => '📋',
            'ac-addon-jetengine'       => '🚀',
            'ac-addon-metabox'         => '🚀',
            'ac-addon-pods'            => '🚀',
            'ac-addon-types'           => '🚀',
            'ac-addon-woocommerce'     => '🛒',
            'ac-addon-yoast-seo'       => '🔍',
            'ac-addon-seopress'        => '🔍',
        ];

        $notices = [];

        foreach ($this->integration_repository->find_all() as $integration) {
            $slug = $integration->get_slug();

            if (isset($icons[$slug])) {
                $notices[] = new Suggestion\IntegrationNotice($integration, $icons[$slug]);
            }
        }

        return $notices;
    }

}