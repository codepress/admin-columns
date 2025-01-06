<?php

namespace AC;

use AC\Admin\Page\Columns;
use AC\Type\Url\Editor;

class PluginActionLinks implements Registerable
{

    private $plugin;

    public function __construct(AdminColumns $plugin)
    {
        $this->plugin = $plugin;
    }

    public function register(): void
    {
        add_filter('plugin_action_links', [$this, 'add_settings_link'], 1, 2);
        add_filter('network_admin_plugin_action_links', [$this, 'add_settings_link'], 1, 2);
    }

    public function add_settings_link($links, $file)
    {
        if ($file === $this->plugin->get_basename()) {
            array_unshift(
                $links,
                sprintf(
                    '<a href="%s">%s</a>',
                    esc_url((new Editor(Columns::NAME))->get_url()),
                    __('Settings', 'codepress-admin-columns')
                )
            );
        }

        return $links;
    }

}