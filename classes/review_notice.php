<?php

class CPAC_Review_Notice {

	const OPTION_INSTALL_DATE     = 'cpac-install-timestamp';
	const OPTION_ADMIN_NOTICE_KEY = 'cpac-hide-review-notice';

	private $days_since_install;
	private $cpac;

	function __construct( $cpac ) {

		register_activation_hook( __FILE__, array( $this, 'insert_install_date' ) );

		// show notice after x days of installing
		$this->days_since_install = 10; // 10 days

		add_action( 'admin_init', array( $this, 'maybe_display_review_notice' ) );
		add_action( 'wp_ajax_cpac_hide_review_notice', array( $this, 'ajax_hide_license_expiry_notice' ) );
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

	public function ajax_hide_license_expiry_notice() {
		add_user_meta( get_current_user_id(), self::OPTION_ADMIN_NOTICE_KEY, '1', true );
	}

	public function display_admin_review_notice() {
		?>
		<div class="cpac_message updated">
			<p>
				<?php _e( "You've been using <b>Admin Columns Pro</b> for some time now, could you please give it a review at wordpress.org?", 'cpac' ); ?><br/>
				<a href='https://wordpress.org/plugins/codepress-admin-columns/' target='_blank'><?php _e( 'Yes, take me there!', 'cpac' ); ?></a> - <a class="hide-review-notice" href='#'><?php _e( "I've already done this!", 'cpac' ); ?></a>
			</p>
		</div>
		<script type="text/javascript">
			jQuery( function( $ ) {
				$( document ).ready( function() {
					$( '.updated a.hide-review-notice' ).click( function( e ) {
						e.preventDefault();
						var el = $( this ).parents( '.cpac_message' );
						$.post( ajaxurl, {
							'action': 'cpac_hide_review_notice'
						}, function( data ) {
							el.slideUp();
						} );
						return false;
					} );
				} );
			} );
		</script>
		<?php
	}
}
