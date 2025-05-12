<?php

namespace AC\Admin\Notice;

use AC\Message;
use AC\Plugin\Install\Database;
use AC\Registerable;
use AC\Screen;
use AC\Service\Setup;

// TODO hook does not exist anymore, how to show this information in Svelte?
final class DatabaseMissing implements Registerable
{

    public function register(): void
    {
        add_action('ac/screen', [$this, 'render_notice']);
    }

    public function render_notice(Screen $screen): void
    {
        global $wpdb;

        if ( ! $screen->is_admin_screen()) {
            return;
        }

        if ( ! Database::verify_database_exists()) {
            $message = sprintf(
                __('Database table %s is missing.', 'codepress-admin-columns'),
                '`' . $wpdb->prefix . 'admin_columns`'
            );

            $message .= ' ' . sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(
                        add_query_arg(Setup::PARAM_FORCE_INSTALL, '1')
                    ),
                    esc_html(
                        __('Create database table.', 'codepress-admin-columns')
                    )
                );

            $notice = new Message\AdminNotice(
                $message,
                Message::ERROR
            );

            echo $notice->render();
        }
    }

}