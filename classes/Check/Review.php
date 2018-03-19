<?php

// TODO finish writing and remove prefix for notice dismiss from the JS
class AC_Check_Review {

	/**
	 * @var int
	 */
	protected $show_after;

	public function __construct( $show_after ) {
		$this->show_after = absint( $show_after );
	}

	public function register() {
		add_action( 'wp_ajax_' . $this->get_dismiss_callback_key(), array( $this, 'ajax_dismiss_notice' ) );
		add_action( 'ac/screen', array( $this, 'display' ) );
	}

	public function display( AC_Screen $screen ) {
		if ( ! $screen->is_ready() || $screen->is_admin_screen() || $screen->is_list_screen() ) {
			return;
		}

		if ( ! $this->first_login_compare() ) {
			return;
		}

		wp_enqueue_script( 'ac-notice-review', AC()->get_plugin_url() . 'assets/js/message-review.js', array( 'jquery' ), AC()->get_version() );

		ac_notice_info( $this->get_message() )
			->set_dismissible_callback( $this->get_dismiss_callback_key() )
			->set_template( 'notice/global-raw' );
	}

	/**
	 * Key used for setting up and catching AJAX callback
	 *
	 * @return string
	 */
	protected function get_dismiss_callback_key() {
		return strtolower( __CLASS__ ) . '_dismiss';
	}

	/**
	 * Check if the amount of days is larger then the first login
	 *
	 * @return bool
	 */
	public function first_login_compare() {
		return time() - $this->show_after * DAY_IN_SECONDS > $this->get_first_login();
	}

	/**
	 * Return the Unix timestamp of first login
	 *
	 * @return integer
	 */
	private function get_first_login() {
		$timestamp = $this->preference()->get( 'first-login-review' );

		if ( empty( $timestamp ) ) {
			$timestamp = time();

			$this->preference()->set( 'first-login-review', $timestamp );
		}

		return $timestamp;
	}

	public function ajax_dismiss_notice() {
		check_ajax_referer( 'ac-ajax' );

		$this->preference()->set( 'dismiss-review', true );
	}

	private function get_message() {
		$product = __( 'Admin Columns', 'codepress-admin-columns' );

		if ( ac_is_pro_active() ) {
			$product = __( 'Admin Columns Pro', 'codepress-admin-columns' );
		}

		ob_start();

		?>

		<div class="info">
			<p>
				<?php printf( __(
					"We don't mean to bug you, but you've been using %s for some time now, and we were wondering if you're happy with the plugin. If so, could you please leave a review at wordpress.org? If you're not happy with %s, please %s.", 'codepress-admin-columns' ),
					'<strong>' . $product . '</strong>',
					$product,
					'<a class="hide-review-notice hide-review-notice-soft" href="#">' . __( 'click here', 'codepress-admin-columns' ) . '</a>'
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
						'<a href="' . esc_url( ac_get_site_utm_url( 'forumns', 'review-notice' ) ) . '" target="_blank">' . __( 'our forum', 'codepress-admin-columns' ) . '</a>'
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