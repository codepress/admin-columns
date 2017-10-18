<?php

class AC_Notice_Review {

	const OPTION_INSTALL_DATE = 'cpac-install-timestamp';

	const OPTION_ADMIN_NOTICE_KEY = 'cpac-hide-review-notice';

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'insert_install_timestamp' ) );

		add_action( 'admin_init', array( $this, 'maybe_display_review_notice' ) );
		add_action( 'wp_ajax_cpac_hide_review_notice', array( $this, 'ajax_hide_review_notice' ) );
	}

	public function insert_install_timestamp() {
		ac_helper()->user->update_meta_site( self::OPTION_INSTALL_DATE, time() );
	}

	private function get_install_timestamp() {
		$timestamp = ac_helper()->user->get_meta_site( self::OPTION_INSTALL_DATE, true );

		if ( '' == $timestamp ) {
			$this->insert_install_timestamp();

			$timestamp = time();
		}

		return $timestamp;
	}

	public function maybe_display_review_notice() {
		if ( AC()->suppress_site_wide_notices() ) {
			return;
		}

		if ( ! AC()->user_can_manage_admin_columns() ) {
			return;
		}

		if ( $this->is_review_notice_shown() ) {
			return;
		}

		// Display notice after 30 days
		if ( ( time() - ( 30 * DAY_IN_SECONDS ) ) >= $this->get_install_timestamp() ) {
			add_action( 'admin_notices', array( $this, 'display_review_notice' ) );
		}
	}

	public function is_review_notice_shown() {
		return '1' === get_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_KEY, true );
	}

	public function ajax_hide_review_notice() {
		update_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_KEY, '1', true );
	}

	public function display_review_notice() {
		$product = __( 'Admin Columns', 'codepress-admin-columns' );

		if ( ac_is_pro_active() ) {
			$product = __( 'Admin Columns Pro', 'codepress-admin-columns' );
		}

		wp_enqueue_style( 'ac-sitewide-notices' );

		?>
        <div class="ac-message updated">
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
        </div>
        <script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).ready( function() {
					$( '.updated a.hide-review-notice' ).click( function( e ) {
						e.preventDefault();

						var el = $( this ).parents( '.ac-message' );
						var el_close = el.find( '.hide-notice' );
						var soft = $( this ).hasClass( 'hide-review-notice-soft' );

						if ( soft ) {
							el.find( '.info' ).slideUp();
							el.find( '.help' ).slideDown();
						}
						else {
							el_close.hide();
							el_close.after( '<div class="spinner right"></div>' );
							el.find( '.spinner' ).show();
						}

						$.post( ajaxurl, {
							'action' : 'cpac_hide_review_notice'
						}, function() {
							if ( !soft ) {
								el.find( '.spinner' ).remove();
								el.slideUp();
							}
						} );

						return false;
					} );
				} );
			} );
        </script>
		<?php
	}

}