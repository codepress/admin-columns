<?php

class AC_Notices_Review {

	public function __construct() {
		add_action( 'current_screen', array( $this, 'register_notice' ) );
		add_action( 'wp_ajax_ac_notice_dismiss_review', array( $this, 'ajax_notice_dismiss' ) );
	}

	public function register_notice() {
		// TODO: dimissed? ;)
		if ( $this->is_dimissed() ) {
			return;
		}

		if ( AC()->suppress_site_wide_notices() ) {
			return;
		}

		if ( ! AC()->user_can_manage_admin_columns() ) {
			return;
		}

		if ( ! $this->days_past_since_first_login( 30 ) ) {
			return;
		}

		wp_enqueue_script( 'ac-notice-review', AC()->get_plugin_url() . "assets/js/message-review.js", array( 'jquery' ), AC()->get_version() );

		$notice = new AC_Notice( $this->get_message() );
		$notice->set_dismissible( true, 'review' );

		AC_Notices::add( $notice );
	}

	/**
	 * Did x days pass after first login
	 *
	 * @return bool
	 */
	private function days_past_since_first_login( $days ) {
		return ( time() - ( $days * DAY_IN_SECONDS ) ) > $this->get_first_login_timestamp();
	}

	/**
	 * @return bool
	 */
	private function is_dimissed() {
		return (bool) $this->preference()->get( 'dismiss-review' );
	}

	/**
	 * @return AC_Preferences
	 */
	private function preference() {
		return new AC_Preferences_Site( 'notices' );
	}

	/**
	 * @return string
	 */
	private function get_first_login_timestamp() {
		$timestamp = $this->preference()->get( 'first-login-review' );

		if ( empty( $timestamp ) ) {
			$timestamp = time();
			$this->preference()->set( 'first-login-review', $timestamp );
		}

		return $timestamp;
	}

	/**
	 * Hide notice
	 */
	public function ajax_notice_dismiss() {
		check_ajax_referer( 'ac-ajax' );

		$this->preference()->set( 'dismiss-review', true );
	}

	protected function get_message() {
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
				<a class="button button-secondary hide-review-notice" href='#'><?php _e( "Permanently hide notice", 'codepress-admin-columns' ); ?></a>
			</p>
		</div>
		<div class="help hidden">
			<a href="#" class="hide-notice hide-review-notice"></a>
			<p>
				<?php printf(
					__( "We're sorry to hear that; maybe we can help! If you're having problems properly setting up %s or if you would like help with some more advanced features, please visit our %s.", 'codepress-admin-columns' ),
					$product,
					'<a href="' . esc_url( ac_get_site_utm_url( 'documentation', 'review-notice' ) ) . '" target="_blank">' . __( 'documentation page', 'codepress-admin-columns' ) . '</a>'
				); ?>
				<?php if ( ac_is_pro_active() ) : ?>
					<?php printf(
						__( 'As an Admin Columns Pro user, you can also use your AdminColumns.com account to access product support through %s!', 'codepress-admin-columns' ),
						'<a href="' . esc_url( ac_get_site_utm_url( 'forumns', 'review-notice' ) ) . '" target="_blank">' . __( 'our forums', 'codepress-admin-columns' ) . '</a>'
					); ?>
				<?php else : ?>
					<?php printf(
						__( 'You can also find help on the %s, and %s.', 'codepress-admin-columns' ),
						'<a href="https://wordpress.org/support/plugin/codepress-admin-columns#postform" target="_blank">' . __( 'Admin Columns forums on WordPress.org', 'codepress-admin-columns' ) . '</a>',
						'<a href="https://wordpress.org/plugins/codepress-admin-columns/faq/#plugin-info" target="_blank">' . __( 'find answers to some frequently asked questions', 'codepress-admin-columns' ) . '</a>'
					); ?>
				<?php endif; ?>
			</p>
		</div>
		<div class="clear"></div>
		<?php

		return ob_get_clean();
	}

}