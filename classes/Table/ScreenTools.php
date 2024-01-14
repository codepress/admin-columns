<?php

namespace AC\Table;

use AC\Registerable;

final class ScreenTools implements Registerable
{

    public function register(): void
    {
        add_action('ac/table', function (Screen $screen) {
            $list_screen = $screen->get_list_screen();

            if ( ! $list_screen) {
                return;
            }

            add_filter('screen_settings', [$this, 'render']);
        });
    }

    public function render($html): string
    {
        ob_start();

        ?>

		<div id="acp-screen-option-tools">
		</div>

        <?php

        $html .= ob_get_clean();

        return $html;
    }

}