<?php

declare(strict_types=1);

namespace AC\Admin\Notice;

use AC\Capabilities;
use AC\Message;
use AC\Registerable;
use AC\Screen;
use AC\Service\Setup;
use AC\Storage\Table\AdminColumns;

final class DatabaseMissing implements Registerable
{

    private AdminColumns $table;

    public function __construct(AdminColumns $table)
    {
        $this->table = $table;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'render_notice']);
    }

    public function render_notice(Screen $screen): void
    {
        // TODO David this would alsno normally TRY to run create on the database, we now assume it's here. Check if
        // that is valid.
        if ( ! current_user_can(Capabilities::MANAGE)
             || ! $screen->is_admin_screen()
             || $this->table->exists()
        ) {
            return;
        }

        $message = sprintf(
            __('Database table %s is missing.', 'codepress-admin-columns'),
            '`' . $this->table->get_name() . '`'
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