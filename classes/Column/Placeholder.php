<?php

namespace AC\Column;

use AC\Column;
use AC\Integration;

class Placeholder extends Column
{

    /**
     * @var Integration
     */
    private $integration;

    public function set_integration(Integration $integration): Placeholder
    {
        $this->set_type('placeholder-' . $integration->get_slug())
             ->set_group($integration->get_slug())
             ->set_label($integration->get_title());

        $this->integration = $integration;

        return $this;
    }

    public function get_message()
    {
        ob_start();
        ?>

		<p>
			<strong>
                <?php
                printf(
                    __("The %s integration is available in Admin Columns Pro", 'codepress-admin-columns'),
                    sprintf('<em>%s</em>', $this->get_label())
                ); ?>
			</strong>
		</p>
		<p>
            <?= $this->integration->get_description() ?>
		</p>

		<a target="_blank" href="<?= $this->integration->get_url() ?>" class="button button-primary">
            <?php
            _e('Find out more', 'codepress-admin-columns'); ?>
		</a>
        <?php

        return ob_get_clean();
    }

}