<?php

declare(strict_types=1);

namespace AC\Check\Upsell;

use AC\Ajax;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Preferences;
use AC\Registerable;
use AC\Screen;
use AC\View;

class UpsellNoticeRenderer implements Registerable
{

    /**
     * @var UpsellNotice[]
     */
    private array $notices;

    /**
     * @var string[]
     */
    private static array $active_integration_slugs = [];

    /**
     * @param UpsellNotice[] $notices
     */
    public function __construct(array $notices)
    {
        $this->notices = $notices;
    }

    public function register(): void
    {
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
        $handler->register();

        add_action('admin_notices', function () use ($notice, $handler) {
            echo $this->render($notice, $handler);
        });

        add_action('admin_enqueue_scripts', static function () {
            (new Style('ac-message'))->enqueue();
            (new Script('ac-message'))->enqueue();
        });
    }

    private function resolve(Screen $screen): ?UpsellNotice
    {
        foreach ($this->notices as $notice) {
            if ($notice->is_active($screen)) {
                return $notice;
            }
        }

        return null;
    }

    private function is_dismissed(UpsellNotice $notice): bool
    {
        return (bool)$this->get_preferences($notice)->find('dismiss-notice');
    }

    private function get_preferences(UpsellNotice $notice): Preferences\Preference
    {
        return (new Preferences\UserFactory())->create('upsell-notice-' . $notice->get_slug());
    }

    private function create_ajax_handler(UpsellNotice $notice): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_dismiss_upsell_' . $notice->get_slug())
            ->set_callback(function () use ($notice, $handler) {
                $handler->verify_request();
                $this->get_preferences($notice)->save('dismiss-notice', true);
            });

        return $handler;
    }

    private function render(UpsellNotice $notice, Ajax\Handler $handler): string
    {
        $view = new View([
            'eyebrow'              => $notice->get_eyebrow(),
            'title'                => $notice->get_title(),
            'description'          => $notice->get_description(),
            'features'             => $notice->get_features(),
            'cta_label'            => $notice->get_cta_label(),
            'cta_url'              => $notice->get_cta_url(),
            'secondary_label'      => $notice->get_secondary_label(),
            'secondary_url'        => $notice->get_secondary_url(),
            'dismissible_callback' => $handler->get_params(),
        ]);

        $view->set_template('message/notice/upsell');

        return $view->render();
    }

}
