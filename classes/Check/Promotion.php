<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Preferences\UserFactory;
use AC\Registerable;
use AC\Screen;
use AC\Type\Promo;

final class Promotion implements Registerable
{

    private Promo $promo;

    private UserFactory $preferences_factory;

    public function __construct(Promo $promo, UserFactory $preferences_factory)
    {
        $this->promo = $promo;
        $this->preferences_factory = $preferences_factory;
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
        return $this->preferences_factory->create(
            'check-promo-' . $this->get_individual_slug()
        );
    }

    public function ajax_dismiss_notice(): void
    {
        $this->get_ajax_handler()->verify_request();
        $this->get_preferences()->set('dismiss-notice', true);
    }

    public function display(Screen $screen): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)
             || ! $screen->is_table_screen()
             || $this->get_preferences()->find('dismiss-notice')
        ) {
            return;
        }

        $notice = new Dismissible($this->promo->get_message(), $this->get_ajax_handler());
        $notice->register();
    }
}