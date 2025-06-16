<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;

class PluginUpdate implements Registerable
{

    public function register(): void
    {
        add_action(
            'in_plugin_update_message-' . $this->get_plugin_slug(),
            [$this, 'render_additional_message'],
            10,
            2
        );

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

    protected function get_plugin_slug(): string
    {
        return 'admin-columns/codepress-admin-columns.php';
    }

    protected function get_major_version(string $version): int
    {
        $parts = explode('.', $version);

        return (int)$parts[0];
    }

    public function render_additional_message(array $data, object $response): void
    {
        $current_major = $this->get_major_version($data['Version']);
        $update_major = $this->get_major_version($response->new_version);

        if ($current_major >= $update_major) {
            return;
        }

        $tpl = __(
            'You are upgrading to a new a major version <strong>(version %s)</strong>, which may include <strong>breaking changes</strong>.',
            'codepress-admin-columns'
        );

        $tpl .= ' ' . _x(
                'Please review the %s for details.',
                '%s contains the link to upgrade guide. Label is translated separately.',
                'codepress-admin-columns'
            );

        $tpl = wp_kses($tpl, [
                'strong' => [],
            ]
        );

        $url = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $this->get_upgrade_guide_url($update_major),
            esc_html_x('upgrade guide', 'Anchor text to upgrade guide.', 'codepress-admin-columns')
        );

        $message = sprintf(
            $tpl,
            $update_major,
            $url
        );

        echo '</p><p class="ac-plugin-icon-upgrade">' . $message;
    }

    /**
     * Add upgrade notice to the update message on the updates page in the WordPress admin
     */
    public function add_upgrade_notice_to_response($transient): ?object
    {
        $slug = $this->get_plugin_slug();

        if (
            is_object($transient) &&
            isset($transient->checked, $transient->response) &&
            ! empty($transient->checked[$slug]) &&
            ! empty($transient->response[$slug])
        ) {
            $current_major = $this->get_major_version($transient->checked[$slug]);
            $update_major = $this->get_major_version($transient->response[$slug]->new_version);

            if ($update_major > $current_major) {
                $notice = esc_html__(
                    'Warning: You are upgrading to a new major version of Admin Columns, which can include changes that are not be backward compatible. See the Plugins page for details.',
                    'codepress-admin-columns',
                );

                $transient->response[$this->get_plugin_slug()]->upgrade_notice = $notice;
            }
        }

        return $transient;
    }

    protected function get_upgrade_guide_url(int $version): string
    {
        return sprintf('https://admincolumns.com/upgrade-ac-to-version-%s', $version);
    }

}