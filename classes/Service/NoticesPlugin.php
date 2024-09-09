<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;

class NoticesPlugin implements Registerable
{

    private $basename;

    private static $notifictations = [];

    public function __construct(string $basename)
    {
        $this->basename = $basename;
    }

    public static function add($notifictation)
    {
        self::$notifictations[] = $notifictation;
    }

    public function register(): void
    {
        add_action('after_plugin_row_' . $this->basename, [$this, 'render'], 11);
    }

    public function render(): void
    {
    }

}