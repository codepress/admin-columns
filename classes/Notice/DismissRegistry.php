<?php

declare(strict_types=1);

namespace AC\Notice;

use AC\Ajax;
use AC\Registerable;

final class DismissRegistry implements Registerable
{

    /** @var array<string, DismissHandler> */
    private array $handlers = [];

    public function register(): void
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_dismiss_notice')
            ->set_callback([$this, 'handle_dismiss']);
        $handler->register();
    }

    public function add(string $notice_id, DismissHandler $handler): void
    {
        $this->handlers[$notice_id] = $handler;
    }

    public function create_handler(string $notice_id): Ajax\Handler
    {
        $handler = new Ajax\Handler(false);
        $handler
            ->set_action('ac_dismiss_notice');
        $handler->set_param('notice_id', $notice_id);

        return $handler;
    }

    public function handle_dismiss(): void
    {
        check_ajax_referer(Ajax\Handler::NONCE_ACTION);

        $notice_id = isset($_POST['notice_id']) ? sanitize_text_field($_POST['notice_id']) : '';

        if (! $notice_id || ! isset($this->handlers[$notice_id])) {
            wp_send_json_error();
        }

        $this->handlers[$notice_id]->dismiss();

        wp_send_json_success();
    }

}
