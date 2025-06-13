<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Registerable;

final class PluginUpdate implements Registerable
{

    public function register(): void
    {
        add_action(
            'in_plugin_update_message-admin-columns/codepress-admin-columns.php',
            [$this, 'render_additional_message'],
            10,
            2
        );

        add_action('admin_head', function () {
            $screen = get_current_screen();

            if ( ! $screen || $screen->base !== 'plugins') {
                return;
            }

            ?>

			<style>
				p.ac-plugin-icon-upgrade::before {
					content: '\f534'
				}
			</style>

            <?php
        });
    }

    public function render_additional_message(array $data, object $response): void
    {
        $get_major = static function (string $version): int {
            $parts = explode('.', $version);

            return (int)$parts[0];
        };

        $current_major = $get_major($data['Version']);
        $update_major = $get_major($response->new_version);

        if ($current_major >= $update_major) {
            return;
        }

        $tpl = __(
            'You are upgrading to a new a major version <strong>(version %s)</strong>, which may include <strong>breaking changes</strong>.',
            'codepress-admin-columns'
        );

        $tpl .= _x(
            'Please review the %s for details.',
            '%s contains the link to upgrade guide. Label is translated separately.',
            'codepress-admin-columns'
        );

        $tpl = wp_kses($tpl, [
                'strong' => [],
            ]
        );

        $url = sprintf(
            '<a href="https://admincolumns.com/upgrade-to-version-%s" target="_blank">%s</a>',
            $current_major,
            esc_html_x('upgrade guide', 'Anchor text to upgrade guide.', 'codepress-admin-columns')
        );

        $message = sprintf(
            $tpl,
            $update_major,
            $url
        );

        echo '</p><p class="ac-plugin-icon-upgrade">' . $message;
    }

}