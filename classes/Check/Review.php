<?php

namespace AC\Check;

use AC\Ajax;
use AC\Capabilities;
use AC\Message;
use AC\Preferences;
use AC\Registrable;
use AC\Screen;

class Review
	implements Registrable {

	/**
	 * @var int Show message after x days
	 */
	protected $show_after = 30;

	/**
	 * @param int $show_after_days
	 */
	public function set_show_after( $show_after_days ) {
		$this->show_after = absint( $show_after_days );
	}

	/**
	 * @throws \Exception
	 */
	public function register() {
		add_action( 'ac/screen', array( $this, 'display' ) );

		$this->get_ajax_handler()->register();
	}

	/**
	 * @param Screen $screen
	 */
	public function display( Screen $screen ) {
		if ( ! $screen->has_screen() ) {
			return;
		}

		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		if ( ! $screen->is_admin_screen() && ! $screen->is_list_screen() ) {
			return;
		}

		if ( $this->get_preferences()->get( 'dismiss-review' ) ) {
			return;
		}

		if ( ! $this->first_login_compare() ) {
			return;
		}

		wp_enqueue_script( 'ac-notice-review', AC()->get_url() . 'assets/js/message-review.js', array( 'jquery' ), AC()->get_version() );

		$notice = new Message\Notice\Dismissible( $this->get_message(), $this->get_ajax_handler() );
		$notice
			->set_id( 'review' )
			->register();
	}

	/**
	 * @return Ajax\Handler
	 */
	protected function get_ajax_handler() {
		$handler = new Ajax\Handler();
		$handler
			->set_action( 'ac_check_review_dismiss_notice' )
			->set_callback( array( $this, 'ajax_dismiss_notice' ) );

		return $handler;
	}

	/**
	 * @return Preferences\User
	 */
	protected function get_preferences() {
		return new Preferences\User( 'check-review' );
	}

	/**
	 * Check if the amount of days is larger then the first login
	 * @return bool
	 */
	protected function first_login_compare() {
		return time() - $this->show_after * DAY_IN_SECONDS > $this->get_first_login();
	}

	/**
	 * Return the Unix timestamp of first login
	 * @return integer
	 */
	protected function get_first_login() {
		$timestamp = $this->get_preferences()->get( 'first-login-review' );

		if ( empty( $timestamp ) ) {
			$timestamp = time();

			$this->get_preferences()->set( 'first-login-review', $timestamp );
		}

		return $timestamp;
	}

	/**
	 * Ajax dismiss notice
	 * @since 3.2
	 */
	public function ajax_dismiss_notice() {
		$this->get_ajax_handler()->verify_request();
		$this->get_preferences()->set( 'dismiss-review', true );
	}

	/**
	 * @return string
	 */
	protected function get_message() {
		$product = ac_is_pro_active()
			? __( 'Admin Columns Pro', 'codepress-admin-columns' )
			: __( 'Admin Columns', 'codepress-admin-columns' );

		ob_start();

		?>

		<div class="info">
			<p>
				<?php printf( __(
					"We don't mean to bug you, but you've been using %s for some time now, and we were wondering if you're happy with the plugin. If so, could you please leave a review at wordpress.org? If you're not happy with %s, please %s.", 'codepress-admin-columns' ),
					'<strong>' . $product . '</strong>',
					$product,
					'<a class="hide-review-notice-soft" href="#">' . __( 'click here', 'codepress-admin-columns' ) . '</a>'
				); ?>
			</p>
			<p class="buttons">
				<a class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/codepress-admin-columns?rate=5#postform" target="_blank"><?php _e( 'Leave a review!', 'codepress-admin-columns' ); ?></a>
				<a class="button button-secondary hide-review-notice" href='#' data-dismiss=""><?php _e( "Permanently hide notice", 'codepress-admin-columns' ); ?></a>
			</p>
		</div>
		<div class="help hidden">
			<a href="#" class="hide-notice hide-review-notice"></a>
			<p>
				<?php

				printf(
					__( "We're sorry to hear that; maybe we can help! If you're having problems properly setting up %s or if you would like help with some more advanced features, please visit our %s.", 'codepress-admin-columns' ),
					$product,
					'<a href="' . esc_url( ac_get_site_utm_url( 'documentation', 'review-notice' ) ) . '" target="_blank">' . __( 'documentation page', 'codepress-admin-columns' ) . '</a>'
				);

				if ( ac_is_pro_active() ) {
					printf(
						__( 'You can also use your admincolumns.com account to access support through %s!', 'codepress-admin-columns' ),
						'<a href="' . esc_url( ac_get_site_utm_url( 'topics', 'review-notice' ) ) . '" target="_blank">' . __( 'our forum', 'codepress-admin-columns' ) . '</a>'
					);
				} else {
					printf(
						__( 'You can also find help on the %s, and %s.', 'codepress-admin-columns' ),
						'<a href="https://wordpress.org/support/plugin/codepress-admin-columns#postform" target="_blank">' . __( 'Admin Columns forum on WordPress.org', 'codepress-admin-columns' ) . '</a>',
						'<a href="https://wordpress.org/plugins/codepress-admin-columns/faq/#plugin-info" target="_blank">' . __( 'find answers to frequently asked questions', 'codepress-admin-columns' ) . '</a>'
					);
				}

				?>
			</p>
		</div>

		<?php

		return ob_get_clean();
	}

}