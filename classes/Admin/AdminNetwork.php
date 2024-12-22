<?php

namespace AC\Admin;

use AC\AdminColumns;
use AC\Registerable;

class AdminNetwork implements Registerable
{

    private $request_handler;

    private $location_core;

    private $scripts;

    public function __construct(
        PageNetworkRequestHandlers $request_handler,
        AdminColumns $plugin,
        AdminScripts $scripts
    ) {
        $this->request_handler = $request_handler;
        $this->location_core = $plugin->get_location();
        $this->scripts = $scripts;
    }

    public function register(): void
    {
        add_action('network_admin_menu', [$this, 'init']);
    }

    private function get_menu_page_factory(): MenuPageFactory
    {
        return apply_filters(
            'acp/menu_network_page_factory',
            new MenuPageFactory\SubMenu()
        );
    }

    public function init(): void
    {
        $hook = $this->get_menu_page_factory()->create([
            'parent' => 'settings.php',
            'icon'   => $this->location_core->with_suffix('assets/images/page-menu-icon.svg')->get_url(),
        ]);

        $loader = new AdminLoader($hook, $this->request_handler, $this->scripts);
        $loader->register();
    }

}