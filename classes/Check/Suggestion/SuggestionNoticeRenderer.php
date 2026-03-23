<?php

declare(strict_types=1);

namespace AC\Check\Suggestion;

use AC\Ajax;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Preferences;
use AC\Registerable;
use AC\Screen;
use AC\View;

class SuggestionNoticeRenderer implements Registerable
{

    /**
     * @var SuggestionNotice[]
     */
    private array $notices;

    /**
     * @var string[]
     */
    private static array $active_integration_slugs = [];

    /**
     * @param SuggestionNotice[] $notices
     */
    public function __construct(array $notices)
    {
        $this->notices = $notices;
    }

    public function register(): void
    {
        foreach ($this->notices as $notice) {
            if ( ! $this->is_dismissed($notice)) {
                $this->create_ajax_handler($notice)->register();
            }
        }

        add_action('ac/screen', [$this, 'display']);
    }

    public static function has_active_notice_for(string $integration_slug): bool
    {
        return in_array($integration_slug, self::$active_integration_slugs, true);
    }

    public function display(Screen $screen): void
    {
        $notice = $this->resolve($screen);

        if ( ! $notice) {
            return;
        }

        if ($this->is_dismissed($notice)) {
            return;
        }

        self::$active_integration_slugs[] = $notice->get_integration_slug();

        $handler = $this->create_ajax_handler($notice);

        add_action('admin_notices', function () use ($notice, $handler) {
            echo $this->render($notice, $handler);
        });

        add_action('admin_enqueue_scripts', static function () {
            (new Style('ac-message'))->enqueue();
            (new Script('ac-message'))->enqueue();
        });
    }

    private function resolve(Screen $screen): ?SuggestionNotice
    {
        foreach ($this->notices as $notice) {
            if ($notice->is_active($screen)) {
                return $notice;
            }
        }

        return null;
    }

    private function is_dismissed(SuggestionNotice $notice): bool
    {
        return (bool)$this->get_preferences($notice)->find('dismiss-notice');
    }

    private function get_preferences(SuggestionNotice $notice): Preferences\Preference
    {
        return (new Preferences\UserFactory())->create('suggestion-notice-' . $notice->get_slug());
    }

    private function create_ajax_handler(SuggestionNotice $notice): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_dismiss_suggestion_' . $notice->get_slug())
            ->set_callback(function () use ($notice, $handler) {
                $handler->verify_request();
                $this->get_preferences($notice)->save('dismiss-notice', true);
            });

        return $handler;
    }

    private function render(SuggestionNotice $notice, Ajax\Handler $handler): string
    {
        $view = new View([
            'icon'                 => $notice->get_icon(),
            'eyebrow'              => $notice->get_eyebrow(),
            'title'                => $notice->get_title(),
            'description'          => $notice->get_description(),
            'cta_label'            => $notice->get_cta_label(),
            'cta_url'              => $notice->get_cta_url(),
            'secondary_label'      => $notice->get_secondary_label(),
            'secondary_url'        => $notice->get_secondary_url(),
            'dismissible_callback' => $handler->get_params(),
        ]);

        $view->set_template('message/notice/suggestion');

        return $view->render();
    }

}
