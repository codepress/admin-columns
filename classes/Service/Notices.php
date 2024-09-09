<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;
use AC\Type\Notice;

class Notices implements Registerable
{

    private static $notifications = [];

    public static function add(Notice $notification): void
    {
        self::$notifications[] = $notification;
    }

    public function register(): void
    {
        if (apply_filters('ac/suppress_site_wide_notices', false)) {
            return;
        }

        add_action('admin_notices', [$this, 'render']);
    }

    public function render(): void
    {
        foreach (self::$notifications as $notification) {
            //$notification->display();
        }
    }

}