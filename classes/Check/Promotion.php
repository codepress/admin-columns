<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Promo;
use AC\Registerable;
use AC\Screen;

final class Promotion
    implements Registerable
{

    private $promo;

    private $preference_factory;

    public function __construct(Promo $promo, Preferences\UserFactory $preference_factory)
    {
        $this->promo = $promo;
        $this->preference_factory = $preference_factory;
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
            ->set_action('ac_dismiss_notice_promo_' . $this->get_individual_slug())
            ->set_callback([$this, 'ajax_dismiss_notice']);

        return $handler;
    }

    private function get_individual_slug(): string
    {
        return $this->promo->get_slug() . $this->promo->get_date_range()->get_start()->format('Ymd');
    }

    private function get_preferences(): Preferences\Preference
    {
        return $this->preference_factory->create(
            'check-promo-' . $this->get_individual_slug()
        );
    }

    public function ajax_dismiss_notice(): void
    {
        $this->get_ajax_handler()->verify_request();
        $this->get_preferences()->save('dismiss-notice', true);
    }

    public function display(Screen $screen): void
    {
        if ( ! $this->promo->is_active()
             || ! current_user_can(Capabilities::MANAGE)
             || ! $screen->is_list_screen()
             || $this->get_preferences()->find('dismiss-notice')
        ) {
            return;
        }

        $message = sprintf(__('Get %s now', 'codepress-admin-columns'), '<strong>Admin Columns Pro</strong>');
        $message = sprintf(
            '%s! <a target="_blank" href="%s">%s</a>',
            $this->promo->get_title(),
            $this->promo->get_url()->get_url(),
            $message
        );

        $notice = new Dismissible($message, $this->get_ajax_handler());
        $notice->register();
    }
}