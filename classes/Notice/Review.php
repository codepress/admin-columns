<?php

class AC_Notice_Review extends AC_Notice {

	public function get_name() {
		return 'review';
	}

	public function register() {
		add_action( 'wp_ajax_ac_hide_notice_review', array( $this, 'ajax_dismiss_notice' ) );
	}

	/**
	 * @return bool
	 */
	protected function hide_notice() {
		if ( $this->is_dimissed() ) {
			return true;
		}

		if ( AC()->suppress_site_wide_notices() ) {
			return true;
		}

		if ( ! AC()->user_can_manage_admin_columns() ) {
			return true;
		}

		if ( $this->is_delayed() ) {
			return true;
		}

		return false;
	}

	/**
	 * Delay notice by 30 days since first login
	 *
	 * @return bool
	 */
	private function is_delayed() {
		return ( time() - ( 30 * DAY_IN_SECONDS ) ) <= $this->get_first_login_timestamp();
	}

	/**
	 * @return bool
	 */
	private function is_dimissed() {
		return (bool) $this->preferences()->get( 'hide-' . $this->get_name() );
	}

	/**
	 * @return AC_Preferences
	 */
	private function preferences() {
		return new AC_Preferences_Site( 'notices' );
	}

	/**
	 * @return string
	 */
	private function get_first_login_timestamp() {
		$timestamp = $this->preferences()->get( 'first-login-' . $this->get_name() );

		if ( empty( $timestamp ) ) {
			$timestamp = time();
			$this->preferences()->set( 'first-login-' . $this->get_name(), $timestamp );
		}

		return $timestamp;
	}

	/**
	 * Hide notice
	 */
	public function ajax_dismiss_notice() {
		$this->preferences()->set( 'hide-' . $this->get_name(), true );
	}

	/**
	 * Display review notice after 30 days of first login by an admin
	 */
	public function display() {
		if ( $this->hide_notice() ) {
			return;
		}

		wp_enqueue_script( 'ac-notice-review', AC()->get_plugin_url() . "assets/js/message-review.js", array( 'jquery' ), AC()->get_version() );

		parent::display();
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