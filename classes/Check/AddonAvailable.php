<?php

declare(strict_types=1);

namespace AC\Check;

use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Notice\DismissHandler\PreferenceDismiss;
use AC\Notice\DismissRegistry;
use AC\Preferences;
use AC\Preferences\UserFactory;
use AC\Registerable;
use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\CouponCode;

final class AddonAvailable implements Registerable
{

    private Integration $integration;

    private UserFactory $preferences_factory;

    private DismissRegistry $dismiss_registry;

    public function __construct(Integration $integration, UserFactory $preferences_factory, DismissRegistry $dismiss_registry)
    {
        $this->integration = $integration;
        $this->preferences_factory = $preferences_factory;
        $this->dismiss_registry = $dismiss_registry;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'display']);

        $this->dismiss_registry->add(
            $this->get_notice_id(),
            new PreferenceDismiss($this->get_preferences(), 'dismiss-notice')
        );
    }

    private function get_notice_id(): string
    {
        return 'addon_' . $this->integration->get_slug();
    }

    private function get_preferences(): Preferences\Preference
    {
        return $this->preferences_factory->create(
            'check-addon-available-' . $this->integration->get_slug()
        );
    }

    public function display(Screen $screen): void
    {
        if (
            ! current_user_can(Capabilities::MANAGE)
            || ! $this->integration->show_notice($screen)
            || $this->get_preferences()->find('dismiss-notice')
        ) {
            return;
        }

        $url = new CouponCode($this->integration->get_url(), 'special10');

        $message = sprintf(
            '%s %s',
            sprintf(
                __('Did you know Admin Columns Pro has full support for %s?', 'codepress-admin-columns'),
                sprintf('<strong>%s</strong>', $this->integration->get_title())
            ),
            sprintf(
                '<a href="%s">%s</a>',
                esc_url((string)$url),
                __('Get Admin Columns Pro with 10% discount', 'codepress-admin-columns')
            )
        );

        $notice = new Dismissible($message, $this->dismiss_registry->create_handler($this->get_notice_id()));
        $notice->register();
    }

}
