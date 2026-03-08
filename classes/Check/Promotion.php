<?php

namespace AC\Check;

use AC\Capabilities;
use AC\Message\Notice\Dismissible;
use AC\Notice\DismissHandler\PreferenceDismiss;
use AC\Notice\DismissRegistry;
use AC\Preferences;
use AC\Preferences\UserFactory;
use AC\Registerable;
use AC\Screen;
use AC\Type\Promo;

final class Promotion implements Registerable
{

    private Promo $promo;

    private UserFactory $preferences_factory;

    private DismissRegistry $dismiss_registry;

    public function __construct(Promo $promo, UserFactory $preferences_factory, DismissRegistry $dismiss_registry)
    {
        $this->promo = $promo;
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
        return 'promo_' . $this->get_individual_slug();
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

    private function is_promo_screen(Screen $screen): bool
    {
        return $screen->has_screen() && ($screen->is_table_screen() || $screen->is_admin_screen());
    }

    public function display(Screen $screen): void
    {
        if ( ! current_user_can(Capabilities::MANAGE) ||
             ! $this->is_promo_screen($screen) ||
             $this->get_preferences()->find('dismiss-notice')
        ) {
            return;
        }

        $notice = new Dismissible(
            $this->promo->get_notice_message(),
            $this->dismiss_registry->create_handler($this->get_notice_id())
        );
        $notice->register();
    }
}
