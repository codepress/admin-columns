<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Check\Suggestion\SuggestionNoticeRenderer;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Registerable;
use AC\Screen;
use AC\Type\Integration;
use AC\Type\Url\CouponCode;

final class AddonAvailable implements Registerable
{

    private Integration $integration;

    public function __construct(Integration $integration)
    {
        $this->integration = $integration;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'display']);
        $this->get_ajax_handler()->register();
    }

    private function get_ajax_handler(): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_dismiss_notice_addon_' . $this->integration->get_slug())
            ->set_callback([$this, 'ajax_dismiss_notice']);

        return $handler;
    }

    private function get_preferences(): Preferences\Preference
    {
        return (new Preferences\UserFactory())->create(
            'check-addon-available-' . $this->integration->get_slug()
        );
    }

    public function ajax_dismiss_notice(): void
    {
        $this->get_ajax_handler()->verify_request();
        $this->get_preferences()->save('dismiss-notice', true);
    }

    public function display(Screen $screen): void
    {
        if (
            ! current_user_can(Capabilities::MANAGE)
            || ! $this->integration->show_notice($screen)
            || $this->get_preferences()->find('dismiss-notice')
            || SuggestionNoticeRenderer::has_active_notice_for($this->integration->get_slug())
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

        $notice = new Dismissible($message, $this->get_ajax_handler());
        $notice->register();
    }

}