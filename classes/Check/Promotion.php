<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Preferences;
use AC\Promo;
use AC\Registerable;
use AC\Screen;

// TODO remove?
final class Promotion
    implements Registerable
{

    private Promo $promo;

    public function __construct(Promo $promo)
    {
        $this->promo = $promo;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'display']);

        $this->get_ajax_handler()->register();
    }

    /**
     * @return Ajax\Handler
     */
    private function get_ajax_handler()
    {
        $handler = new Ajax\Handler();

        $handler
            ->set_action('ac_dismiss_notice_promo_' . $this->get_individual_slug())
            ->set_callback([$this, 'ajax_dismiss_notice']);

        return $handler;
    }

    private function get_individual_slug()
    {
        return $this->promo->get_slug() . $this->promo->get_date_range()->get_start()->format('Ymd');
    }

    private function get_preferences(): Preferences\Preference
    {
        return (new Preferences\UserFactory())->create(
            'check-promo-' . $this->get_individual_slug()
        );
    }

    /**
     * Dismiss notice
     */
    public function ajax_dismiss_notice()
    {
        $this->get_ajax_handler()->verify_request();
        $this->get_preferences()->save('dismiss-notice', true);
    }

    /**
     * @param Screen $screen
     */
    public function display(Screen $screen)
    {
        if ( ! $this->promo->is_active()
             || ! current_user_can(Capabilities::MANAGE)
             || ! $screen->is_table_screen()
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