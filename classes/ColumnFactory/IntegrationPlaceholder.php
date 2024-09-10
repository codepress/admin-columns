<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Integration;
use AC\Setting\ComponentFactory\Message;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;

class IntegrationPlaceholder extends BaseColumnFactory
{

    private $integration;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        Integration $integration
    ) {
        parent::__construct($component_factory_registry);

        $this->integration = $integration;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add(new Message(__('Integration', 'codepress-admin-columns'), $this->get_message_body()));
    }

    private function get_message_body(): string
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
                $this->integration->get_title(),
                $this->integration->get_title()
            ); ?>
		</p>

		<a target="_blank" href="<?php
        echo $this->integration->get_link(); ?>" class="button button-primary">
            <?php
            _e('Find out more', 'codepress-admin-columns'); ?>
		</a>
        <?php

        return ob_get_clean();
    }

    public function get_label(): string
    {
        return $this->integration->get_title();
    }

    public function get_column_type(): string
    {
        return 'placeholder-' . $this->integration->get_slug();
    }

}