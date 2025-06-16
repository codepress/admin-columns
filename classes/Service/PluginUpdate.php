<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Entity\Plugin;
use AC\Plugin\Version;
use AC\Registerable;
use AC\Type\Url\Site;

class PluginUpdate implements Registerable
{

    private Plugin $plugin;

    private Site $upgrade_url_template;

    public function __construct(Plugin $plugin, Site $upgrade_url_template)
    {
        $this->plugin = $plugin;
        $this->upgrade_url_template = $upgrade_url_template;
    }

    public function register(): void
    {
        add_action(
            'in_plugin_update_message-' . $this->plugin->get_basename(),
            [$this, 'render_additional_message'],
            10,
            2
        );
        wp_clean_update_cache();

        // Use wp_clean_update_cache() to bypass cache
        add_filter('pre_set_site_transient_update_plugins', [$this, 'add_upgrade_notice_to_response'], 20);

        add_action('admin_head', function () {
            $screen = get_current_screen();

            if ( ! $screen || $screen->base !== 'plugins') {
                return;
            }

            ?>

			<style>
				p.ac-plugin-icon-upgrade::before {
					content: '\f534' !important;
				}
			</style>

            <?php
        });
    }

    private function get_general_warning_message(): string
    {
        return __(
            'You are upgrading to a new a major version of Admin Columns, which can include <strong>breaking changes</strong>.',
            'codepress-admin-columns'
        );
    }

    public function render_additional_message(array $data, object $response): void
    {
        $version = new Version($response->new_version);

        if ($this->plugin->get_version()->get_major_version() >= $version->get_major_version()) {
            return;
        }

        $tpl = $this->get_general_warning_message() . ' ' . _x(
                'Please review the %s for details.',
                '%s contains the link to upgrade guide. Label is translated separately.',
                'codepress-admin-columns'
            );

        $tpl = wp_kses($tpl, [
                'strong' => [],
            ]
        );

        $url = sprintf((string)$this->upgrade_url_template, $version->get_major_version());

        $url = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $url,
            esc_html_x('upgrade guide', 'Anchor text to upgrade guide.', 'codepress-admin-columns')
        );

        $message = sprintf(
            $tpl,
            $url
        );

        echo '</p><p class="ac-plugin-icon-upgrade">' . $message;
    }

    /**
     * Add upgrade notice to the update message on the updates page in the WordPress admin
     */
    public function add_upgrade_notice_to_response($transient): ?object
    {
        $basename = $this->plugin->get_basename();

        if (
            is_object($transient) &&
            isset($transient->checked, $transient->response) &&
            ! empty($transient->checked[$basename]) &&
            ! empty($transient->response[$basename])
        ) {
            $update_major = (new Version($transient->response[$basename]->new_version))->get_major_version();

            if ($update_major > $this->plugin->get_version()->get_major_version()) {
                $notice = sprintf(
                    '%s: %s %s',
                    esc_html__('Warning'),
                    strip_tags($this->get_general_warning_message()),
                    esc_html__('See the Plugins page for details.', 'codepress-admin-columns')
                );

                $transient->response[$basename]->upgrade_notice = $notice;
            }
        }

        return $transient;
    }

}