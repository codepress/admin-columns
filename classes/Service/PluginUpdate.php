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
            [$this, 'renderAdditionalMessage'],
            10,
            2
        );
    }

    public function renderAdditionalMessage(array $data, object $response): void
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

        $tpl = '
			<br><br>
			<span class="dashicons dashicons-warning" style="padding-right: 3px; color: var(--ac-color-error);"></span>
			You are upgrading to a new a major version <strong>(v%s)</strong>, which may include <strong>breaking changes</strong>. 
			Please review the <a href="https://admincolumns.com/upgrade-to-v%s" target="_blank">upgrade guide</a> for details.
		';

        $notice = sprintf(
            $tpl,
            $update_major,
            $update_major
        );

        $message = wp_kses($notice, [
                'span'   => ['class' => true, 'style' => true],
                'strong' => [],
                'br'     => [],
                'a'      => ['href' => true],
            ]
        );

        echo trim($message);
    }

}