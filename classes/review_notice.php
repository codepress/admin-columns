<?php

class CPAC_Review_Notice {

	const OPTION_INSTALL_DATE     = 'cpac-install-timestamp';
	const OPTION_ADMIN_NOTICE_KEY = 'cpac-hide-review-notice';

	private $days_since_install;
	private $cpac;

	function __construct( $cpac ) {

		register_activation_hook( __FILE__, array( $this, 'insert_install_timestamp' ) );

		// show notice after x days of installing
		$this->days_since_install = 30; // 30 days

		add_action( 'admin_init', array( $this, 'maybe_display_review_notice' ) );
		add_action( 'wp_ajax_cpac_hide_review_notice', array( $this, 'ajax_hide_review_notice' ) );
	}

	public function insert_install_timestamp() {
		add_site_option( self::OPTION_INSTALL_DATE, time() );
		return time();
	}

	private function get_install_timestamp() {
		$timestamp = get_site_option( self::OPTION_INSTALL_DATE, '' );
		if ( '' == $timestamp ) {
			$timestamp = $this->insert_install_timestamp();
		}
		return $timestamp;
	}

	public function maybe_display_review_notice() {
		if ( current_user_can( 'manage_admin_columns' ) && ( ! get_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_KEY, true ) ) ) {
			if ( ( time() - ( 86400 * absint( $this->days_since_install ) ) ) >= $this->get_install_timestamp() ) {
				add_action( 'admin_notices', array( $this, 'display_admin_review_notice' ) );
			}
		}
	}

	public function ajax_hide_review_notice() {
		update_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_KEY, '1', true );
	}

	public function display_admin_review_notice() {
		$screen = get_current_screen();

		// only display on settings and plugins page
		if ( ! $screen || ! in_array( $screen->parent_base, array( 'options-general', 'plugins' ) ) ) {
			return false;
		}

		$product = __( 'Admin Columns', 'cpac' );

		if ( defined( 'ACP_VERSION' ) ) {
			$product = __( 'Admin Columns Pro', 'cpac' );
		}
		?>
		<div class="cpac_message updated">
			<div class="info">
				<p>
					<?php printf( __(
						"We don't mean to bug you, but you've been using %s for some time now, and we were wondering if you're happy with the plugin. If so, could you please leave a review at wordpress.org? If you're not happy with %s, please %s.", 'cpac' ),
						'<strong>' . $product . '</strong>',
						$product,
						'<a class="hide-review-notice hide-review-notice-soft" href="#">' . __( 'click here', 'cpac' ) . '</a>'
					); ?>
				</p>
				<p class="buttons">
					<a class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/codepress-admin-columns?rate=5#postform" target="_blank"><?php _e( 'Leave a review!', 'cpac' ); ?></a>
					<a class="button button-secondary hide-review-notice" href='#'><?php _e( "Permanently hide notice", 'cpac' ); ?></a>
				</p>
			</div>
			<div class="help">
				<a href="#" class="hide-notice hide-review-notice"></a>
				<p>
					<?php printf(
						__( "We're sorry to hear that; maybe we can help! If you're having problems properly setting up %s or if you would like help with some more advanced features, please visit our %s.", 'cpac' ),
						$product,
						'<a href="http://admincolumns.com/documentation/" target="_blank">' . __( 'documentation page', 'cpac' ) . '</a>'
					); ?>
					<?php if ( defined( 'ACP_VERSION' ) ) : ?>
						<?php printf(
							__( 'As an Admin Columns Pro user, you can also use your AdminColumns.com account to access product support through %s!', 'cpac' ),
							'<a href="https://www.admincolumns.com/forums/" target="_blank">' . __( 'our forums', 'cpac' ) . '</a>'
						); ?>
					<?php else : ?>
						<?php printf(
							__( 'You can also find help on the %s, and %s.', 'cpac' ),
							'<a href="https://wordpress.org/support/plugin/codepress-admin-columns#postform" target="_blank">' . __( 'Admin Columns forums on WordPress.org', 'cpac' ) . '</a>',
							'<a href="https://wordpress.org/plugins/codepress-admin-columns/faq/#plugin-info" target="_blank">' . __( 'find answers to some frequently asked questions', 'cpac' ) . '</a>'
						); ?>
					<?php endif; ?>
				</p>
			</div>
			<div class="clear"></div>
		</div>
		<style type="text/css">
			body .wrap .cpac_message {
				position: relative;
				padding-right: 40px;
			}
			.cpac_message .spinner.right {
				visibility: visible;
				display: block;
				right: 8px;
				text-decoration: none;
				text-align: right;
				position: absolute;
				top: 50%;
				margin-top: -10px;
			}
			.cpac_message .spinner.inline {
				display: inline-block;
				position: absolute;
				margin: 4px 0 0 4px;
				padding: 0;
				float: none;
			}
			.cpac_message .hide-notice {
				right: 8px;
				text-decoration: none;
				width: 32px;
				text-align: right;
				position: absolute;
				top: 50%;
				height: 32px;
				margin-top: -16px;
			}
			.cpac_message .hide-notice:before {
				display: block;
				content: '\f335';
				font-family: 'Dashicons';
				margin: .5em 0;
				padding: 2px;
			}
			.cpac_message .buttons {
				margin-top: 8px;
			}
			.cpac_message .help {
				display: none;
			}
		</style>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).ready( function() {
					$( '.updated a.hide-review-notice' ).click( function( e ) {
						e.preventDefault();

						var el = $( this ).parents( '.cpac_message' );
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
							'action': 'cpac_hide_review_notice'
						}, function( data ) {
							if ( ! soft ) {
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
