<?php

declare(strict_types=1);

namespace AC\Check;

use AC\Asset\Location;
use AC\Asset\Script;
use AC\Capabilities;
use AC\Message;
use AC\Notice\DismissHandler\PreferenceDismiss;
use AC\Notice\DismissRegistry;
use AC\Preferences;
use AC\Preferences\UserFactory;
use AC\Registerable;
use AC\Screen;
use AC\Type\Url\Documentation;
use AC\Type\Url\UtmTags;
use AC\View;

final class Review implements Registerable
{

    private Location $location;

    private UserFactory $preferences_factory;

    private DismissRegistry $dismiss_registry;

    public function __construct(Location $location, UserFactory $preferences_factory, DismissRegistry $dismiss_registry)
    {
        $this->location = $location;
        $this->preferences_factory = $preferences_factory;
        $this->dismiss_registry = $dismiss_registry;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'display']);

        $this->dismiss_registry->add(
            'review',
            new PreferenceDismiss($this->get_preferences(), 'dismiss-review')
        );
    }

    public function display(Screen $screen): void
    {
        if ( ! $screen->has_screen()) {
            return;
        }

        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        if ( ! $screen->is_admin_screen() && ! $screen->is_table_screen()) {
            return;
        }

        if ($this->get_preferences()->find('dismiss-review')) {
            return;
        }

        if ( ! $this->first_login_compare()) {
            return;
        }

        $script = new Script('ac-notice-review', $this->location->with_suffix('assets/js/message-review.js'), ['jquery']
        );
        $script->enqueue();

        $notice = new Message\Notice\Dismissible($this->get_message(), $this->dismiss_registry->create_handler('review'));
        $notice
            ->set_id('review')
            ->register();
    }

    private function get_preferences(): Preferences\Preference
    {
        return $this->preferences_factory->create('check-review');
    }

    private function first_login_compare(): bool
    {
        // Show after 30 days
        return time() - (30 * DAY_IN_SECONDS) > $this->get_first_login();
    }

    /**
     * Return the Unix timestamp of first login
     */
    private function get_first_login(): int
    {
        $timestamp = $this->get_preferences()->find('first-login-review');

        if (empty($timestamp)) {
            $timestamp = time();

            $this->get_preferences()->save('first-login-review', $timestamp);
        }

        return $timestamp;
    }

    private function get_documentation_url(): string
    {
        return (new UtmTags(new Documentation(), 'review-notice'))->get_url();
    }

    private function get_message(): string
    {
        $view = new View([
            'documentation_url' => $this->get_documentation_url(),
        ]);
        $view->set_template('message/review');

        return $view->render();
    }

}
