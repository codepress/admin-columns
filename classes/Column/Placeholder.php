<?php

namespace AC\Column;

use AC\Column;
use AC\Integration;
use AC\Type\Url\Editor;

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 * @since 2.2
 */
class Placeholder extends Column
{

    /**
     * @var Integration
     */
    private $integration;

    /**
     * @param Integration $integration
     *
     * @return $this
     */
    public function set_integration(Integration $integration)
    {
        $this->set_type('placeholder-' . $integration->get_slug())
             ->set_group($integration->get_slug())
             ->set_label($integration->get_title());

        $this->integration = $integration;

        return $this;
    }

    private function get_addons_page_url()
    {
        return new Editor('addons');
    }

    public function get_message()
    {
        ob_start();
        ?>

		<p>
			<strong><?php
                printf(
                    __("%s support is only available in Admin Columns Pro.", 'codepress-admin-columns'),
                    $this->get_label()
                ); ?></strong>
		</p>
		<p>
            <?php
            printf(
                __(
                    "Admin Columns Pro offers full support for %s, allowing you to easily manage %s fields for your overviews.",
                    'codepress-admin-columns'
                ),
                $this->get_label(),
                $this->get_label()
            ); ?>
		</p>

		<a target="_blank" href="<?= $this->integration->get_url() ?>" class="button button-primary">
            <?php
            _e('Find out more', 'codepress-admin-columns'); ?>
		</a>
        <?php

        return ob_get_clean();
    }

}