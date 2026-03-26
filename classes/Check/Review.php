<?php

namespace AC\Check;

use AC\Ajax;
use AC\Asset\Location;
use AC\Asset\Script;
use AC\Capabilities;
use AC\Message;
use AC\Notice\NoticeState;
use AC\Registerable;
use AC\Screen;
use AC\Type\Url\Documentation;
use AC\Type\Url\UtmTags;

final class Review implements Registerable
{

    private const SLUG = 'review';
    private const DELAY_DAYS = 30;

    private Location $location;

    private NoticeState $state;

    public function __construct(Location $location, NoticeState $state)
    {
        $this->location = $location;
        $this->state = $state;
    }

    public function register(): void
    {
        add_action('ac/screen', [$this, 'display']);

        $this->get_ajax_handler()->register();
    }

    public function display(Screen $screen): void
    {
        if ( ! current_user_can(Capabilities::MANAGE)) {
            return;
        }

        if ( ! $screen->has_screen()) {
            return;
        }

        if ( ! $screen->is_admin_screen()) {
            return;
        }

        if ($this->state->is_dismissed(self::SLUG)) {
            return;
        }

        $this->state->track_first_seen(self::SLUG);

        if ( ! $this->state->is_delay_met(self::SLUG, self::DELAY_DAYS)) {
            return;
        }

        $script = new Script('ac-notice-review', $this->location->with_suffix('assets/js/message-review.js'), ['jquery']
        );
        $script->enqueue();

        $notice = new Message\Notice\Dismissible($this->get_message(), $this->get_ajax_handler());
        $notice
            ->set_id('review')
            ->register();
    }

    protected function get_ajax_handler(): Ajax\Handler
    {
        $handler = new Ajax\Handler();
        $handler
            ->set_action('ac_check_review_dismiss_notice')
            ->set_callback([$this, 'ajax_dismiss_notice']);

        return $handler;
    }

    public function ajax_dismiss_notice(): void
    {
        $this->get_ajax_handler()->verify_request();
        $this->state->dismiss(self::SLUG);
    }

    private function get_documentation_url(): string
    {
        return (new UtmTags(new Documentation(), 'review-notice'))->get_url();
    }

    protected function get_message(): string
    {
        $product = __('Admin Columns', 'codepress-admin-columns');

        ob_start();

        ?>

		<div class="info">
			<p>
                <?php
                printf(
                    __(
                        "We don't mean to bug you, but you've been using %s for some time now, and we were wondering if you're happy with the plugin. If so, could you please leave a review at wordpress.org? If you're not happy with %s, please %s.",
                        'codepress-admin-columns'
                    ),
                    '<strong>' . $product . '</strong>',
                    $product,
                    '<a class="hide-review-notice-soft" href="#">' . __(
                        'click here',
                        'codepress-admin-columns'
                    ) . '</a>'
                ); ?>
			</p>
			<p class="buttons">
				<a class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/codepress-admin-columns?rate=5#postform" target="_blank"><?php
                    _e('Leave a review!', 'codepress-admin-columns'); ?></a>
				<a class="button button-secondary hide-review-notice" href='#' data-dismiss=""><?php
                    _e("Permanently hide notice", 'codepress-admin-columns'); ?></a>
			</p>
		</div>
		<div class="help hidden">
			<a href="#" class="hide-notice hide-review-notice"></a>
			<p>
                <?php

                printf(
                    __(
                        "We're sorry to hear that; maybe we can help! If you're having problems properly setting up %s or if you would like help with some more advanced features, please visit our %s.",
                        'codepress-admin-columns'
                    ),
                    $product,
                    '<a href="' . esc_url(
                        $this->get_documentation_url()
                    ) . '" target="_blank">' . __(
                        'documentation page',
                        'codepress-admin-columns'
                    ) . '</a>'
                );

                printf(
                    __('You can also find help on the %s, and %s.', 'codepress-admin-columns'),
                    '<a href="https://wordpress.org/support/plugin/codepress-admin-columns#postform" target="_blank">' . __(
                        'Admin Columns forum on WordPress.org',
                        'codepress-admin-columns'
                    ) . '</a>',
                    '<a href="https://wordpress.org/plugins/codepress-admin-columns/#faq" target="_blank">' . __(
                        'find answers to frequently asked questions',
                        'codepress-admin-columns'
                    ) . '</a>'
                );

                ?>
			</p>
		</div>

        <?php

        return ob_get_clean();
    }

}
