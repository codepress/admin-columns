<?php

namespace AC\Admin\Notice;

use AC\Capabilities;
use AC\Message;
use AC\Plugin\Install\Database;
use AC\Registerable;
use AC\Screen;
use AC\Service\Setup;

final class DatabaseMissing implements Registerable
{

    public function register(): void
    {
        add_action('ac/screen', [$this, 'render_notice']);
    }

    public function render_notice(Screen $screen): void
    {
        global $wpdb;

        if ( ! current_user_can(Capabilities::MANAGE)
             || ! $screen->is_admin_screen()
             || Database::verify_database_exists()) {
            return;
        }

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