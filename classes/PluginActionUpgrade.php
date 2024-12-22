<?php

namespace AC;

use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class PluginActionUpgrade implements Registerable
{

    private $plugin;

    public function __construct(AdminColumns $plugin)
    {
        $this->plugin = $plugin;
    }

    public function register(): void
    {
        add_filter('plugin_action_links', [$this, 'add_settings_link'], 2, 2);
        add_filter('network_admin_plugin_action_links', [$this, 'add_settings_link'], 2, 2);
    }

    public function add_settings_link($links, $file)
    {
        if ($file === $this->plugin->get_basename()) {
            $links[] = sprintf(
                '<a href="%s" target="_blank">%s</a>',
                esc_url((new UtmTags(new Site(Site::PAGE_ABOUT_PRO), 'upgrade'))->get_url()),
                sprintf(
                    '<span style="font-weight: bold;">%s</span>',
                    __('Go Pro', 'codepress-admin-columns')
                )
            );
        }

        return $links;
    }

}