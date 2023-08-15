<?php

namespace AC\Admin;

use AC\Asset\Location\Absolute;
use AC\Registerable;

class Admin implements Registerable
{

    public const NAME = 'codepress-admin-columns';

    private $request_handler;

    private $location;

    private $scripts;

    public function __construct(RequestHandlerInterface $request_handler, Absolute $location, AdminScripts $scripts)
    {
        $this->request_handler = $request_handler;
        $this->location = $location;
        $this->scripts = $scripts;
    }

    public function register(): void
    {
        add_action('admin_menu', [$this, 'init']);
    }

    private function get_menu_page_factory(): MenuPageFactory
    {
        return apply_filters(
            'ac/menu_page_factory',
            new MenuPageFactory\SubMenu()
        );
    }

    public function init(): void
    {
        $hook = $this->get_menu_page_factory()->create([
            'parent' => 'options-general.php',
            'icon'   => $this->location->with_suffix('assets/images/page-menu-icon.svg')->get_url(),
        ]);

        $loader = new AdminLoader($hook, $this->request_handler, $this->scripts);
        $loader->register();
    }

}