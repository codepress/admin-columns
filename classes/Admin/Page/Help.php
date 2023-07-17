<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\RenderableHead;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\Deprecated\Hooks;
use AC\Renderable;
use AC\Type\Url;

class Help implements Enqueueables, Renderable, RenderableHead
{

    public const NAME = 'help';

    private $hooks;

    private $location;

    private $head;

    public function __construct(Hooks $hooks, Location\Absolute $location, Renderable $head)
    {
        $this->hooks = $hooks;
        $this->location = $location;
        $this->head = $head;
    }

    public function render_head(): Renderable
    {
        return $this->head;
    }

    public function get_assets(): Assets
    {
        return new Assets([
            new Style('ac-admin-page-help-css', $this->location->with_suffix('assets/css/admin-page-help.css')),
        ]);
    }

    private function get_documention_link(): string
    {
        return sprintf(
            '<a href="%s" target="_blank">%s &raquo;</a>',
            (new Url\Documentation(Url\Documentation::ARTICLE_UPGRADE_V3_TO_V4))->get_url(),
            __('View documentation', 'codepress-admin-columns')
        );
    }

    private function get_callback_message(array $callbacks): string
    {
        return sprintf(
            _n('The callback used is %s.', 'The callbacks used are %s.', count($callbacks), 'codepress-admin-columns'),
            '<code>' . implode('</code>, <code>', $callbacks) . '</code>'
        );
    }

    private function render_actions(): void
    {
        $hooks = $this->hooks->get_deprecated_actions();

        if ( ! $hooks) {
            return;
        }

        ?>
		<h3><?php
            __('Deprecated Actions', 'codepress-admin-columns'); ?></h3>
        <?php

        foreach ($hooks as $hook) {
            $message = sprintf(
                __('The action %s used on this website is deprecated since %s.', 'codepress-admin-columns'),
                '<code>' . $hook->get_name() . '</code>',
                '<strong>' . $hook->get_version() . '</strong>'
            );

            $callbacks = $hook->get_callbacks();

            if ($callbacks) {
                $message .= ' ' . $this->get_callback_message($callbacks);
            }

            $message .= ' ' . $this->get_documention_link();

            $this->render_message($message);
        }
    }

    private function render_filters(): void
    {
        $hooks = $this->hooks->get_deprecated_filters();

        if ( ! $hooks) {
            return;
        }

        ?>
		<h3><?php
            __('Deprecated Filters', 'codepress-admin-columns'); ?></h3>
        <?php

        foreach ($hooks as $hook) {
            $message = sprintf(
                __('The filter %s used on this website is deprecated since %s.', 'codepress-admin-columns'),
                '<code>' . $hook->get_name() . '</code>',
                '<strong>' . $hook->get_version() . '</strong>'
            );

            $callbacks = $hook->get_callbacks();

            if ($callbacks) {
                $message .= ' ' . $this->get_callback_message($callbacks);
            }

            $message .= ' ' . $this->get_documention_link();

            $this->render_message($message);
        }
    }

    private function render_message(string $message): void
    {
        ?>
		<div class="ac-deprecated-message">
			<p><?= $message ?></p>
		</div>
        <?php
    }

    public function render(): string
    {
        // Force cache refresh
        $this->hooks->get_count(true);

        ob_start();
        ?>
		<h2><?php
            _e('Help', 'codepress-admin-columns'); ?></h2>
		<p>
            <?php
            _e('The Admin Columns plugin has undergone some major changes in version 4.', 'codepress-admin-columns'); ?>
			<br/>

            <?php
            printf(
                __(
                    'This site is using some actions or filters that have changed. Please read %s to resolve them.',
                    'codepress-admin-columns'
                ),
                sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    (new Url\Documentation(Url\Documentation::ARTICLE_UPGRADE_V3_TO_V4))->get_url(),
                    __('our documentation', 'codepress-admin-columns')
                )
            );
            ?>
		</p>

        <?php

        if ($this->hooks->get_count() > 0) {
            $this->render_actions();
            $this->render_filters();
        } else {
            printf('<em>%s</em>', __('No deprecated hooks or filters found.', 'codepress-admin-columns'));
        }

        return ob_get_clean();
    }

}