<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Ajax;
use AC\Asset\Script;
use AC\Asset\Style;
use AC\Capabilities;
use AC\Notice\NoticeState;
use AC\Registerable;
use AC\Screen;
use AC\View;

class IntegrationNoticeRenderer implements Registerable
{

    private const DELAY_DAYS = 14;

    /**
     * @var IntegrationNotice[]
     */
    private array $notices;

    /**
     * @var Ajax\Handler[]
     */
    private array $handlers = [];

    private NoticeState $state;

    /**
     * @param IntegrationNotice[] $notices
     */
    public function __construct(array $notices, NoticeState $state)
    {
        $this->notices = $notices;
        $this->state = $state;
    }

    public function register(): void
    {
        foreach ($this->notices as $notice) {
            if ( ! $this->state->is_dismissed($notice->get_slug())) {
                $handler = $this->create_ajax_handler($notice);
                $handler->register();
                $this->handlers[$notice->get_slug()] = $handler;
            }
        }

        add_action('ac/screen', [$this, 'display']);
    }

    public function display(Screen $screen): void
    {
        $notice = $this->resolve($screen);

        if ( ! $notice) {
            return;
        }

        $slug = $notice->get_slug();

        $this->state->track_first_seen($slug);

        if ( ! $this->is_ready_to_show($notice)) {
            return;
        }

        $handler = $this->handlers[$slug] ?? null;

        if ( ! $handler) {
            return;
        }

        add_action('admin_notices', function () use ($notice, $handler) {
            echo $this->render($notice, $handler);
        });

        add_action('admin_enqueue_scripts', static function () {
            (new Style('ac-message'))->enqueue();
            (new Script('ac-message'))->enqueue();
        });
    }

    private function resolve(Screen $screen): ?IntegrationNotice
    {
        foreach ($this->notices as $notice) {
            if ($notice->is_active($screen)) {
                return $notice;
            }
        }

        return null;
    }

    private function is_ready_to_show(IntegrationNotice $notice): bool
    {
        if ( ! $this->state->is_delay_met($notice->get_slug(), self::DELAY_DAYS)) {
            return false;
        }

        if ($notice instanceof UsageAwareNotice && ! $notice->is_usage_detected()) {
            return false;
        }

        return true;
    }

    private function create_ajax_handler(IntegrationNotice $notice): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_dismiss_suggestion_' . $notice->get_slug())
            ->set_callback(function () use ($notice, $handler) {
                $handler->verify_request();

                if ( ! current_user_can(Capabilities::MANAGE)) {
                    wp_die('-1');
                }

                $this->state->dismiss($notice->get_slug());
            });

        return $handler;
    }

    private function render(IntegrationNotice $notice, Ajax\Handler $handler): string
    {
        $view = new View([
            'eyebrow'              => $notice->get_eyebrow(),
            'title'                => $notice->get_title(),
            'description'          => $notice->get_description(),
            'cta_label'            => $notice->get_cta_label(),
            'cta_url'              => $notice->get_cta_url(),
            'secondary_label'      => $notice->get_secondary_label(),
            'secondary_url'        => $notice->get_secondary_url(),
            'dismissible_callback' => $handler->get_params(),
            'extra_classes'        => $notice->get_extra_classes(),
        ]);

        $view->set_template('message/notice/integration');

        return $view->render();
    }

}
