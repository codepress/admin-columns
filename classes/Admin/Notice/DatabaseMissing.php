<?php

namespace AC\Admin\Notice;

use AC\ListScreen;
use AC\Message;
use AC\Plugin\Install\Database;
use AC\Registerable;
use AC\Service\Setup;

// TODO hook does not exist anymore, how to show this information in Svelte?
final class DatabaseMissing implements Registerable
{

    public function register(): void
    {
        add_action('ac/settings/notice', [$this, 'render_notice']);
    }

    public function render_notice(ListScreen $list_screen): void
    {
        global $wpdb;

        if ( ! Database::verify_database_exists()) {
            $message = sprintf(
                __('Database table %s is missing.', 'codepress-admin-columns'),
                '`' . $wpdb->prefix . 'admin_columns`'
            );

            $message .= ' ' . sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(
                        (string)$list_screen->get_editor_url()->with_arg(Setup::PARAM_FORCE_INSTALL, '1')
                    ),
                    esc_html(
                        __('Create database table.', 'codepress-admin-columns')
                    )
                );

            $notice = new Message\InlineMessage(
                sprintf(
                    '<p>%s</p>',
                    $message
                ),
                Message::ERROR
            );

            echo $notice->render();
        }
    }

}