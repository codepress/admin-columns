<?php

namespace AC\Service;

use AC\Plugin;
use AC\Registerable;

final class Setup implements Registerable
{

    public const PARAM_FORCE_INSTALL = 'ac-force-install';

    private Plugin\Setup $setup;

    public function __construct(Plugin\Setup $setup)
    {
        $this->setup = $setup;
    }

    public function register(): void
    {
        add_action('init', [$this, 'run'], 1000);
    }

    public function run(): void
    {
        if (wp_doing_ajax()) {
            return;
        }

        if ( ! is_blog_installed()) {
            return;
        }

        $force_install = '1' === filter_input(INPUT_GET, self::PARAM_FORCE_INSTALL);

        $this->setup->run($force_install);
    }

}