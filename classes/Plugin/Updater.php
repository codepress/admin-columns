<?php

class AC_Plugin_Updater {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * @var bool
	 */
	protected $show_notice;

	/**
	 * @var bool
	 */
	protected $apply_updates;

	protected $first_install;

	protected function __construct() {
		$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
		$this->show_notice = false;
	}

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Check if the plugin was updated or that it's a first install
	 *
	 */
	public function is_plugin_update() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		return is_array( $results ) && ! empty( $results );
	}

	/**
	 * @param AC_Plugin $plugin
	 * @param array     $updates Key should contain version, value should be an array with callbacks
	 */
	public function parse_updates( AC_Plugin_Update $update ) {
		if ( $this->show_notice || ! $update->needs_update() ) {
			return;
		}

		// Stop further checking, queue update message
		if ( ! $this->apply_updates ) {
			// TODO: maybe one hook later and have this class itself on admin_init?
			add_action( 'admin_init', array( $this, 'show_update_notice' ) );

			return;
		}

		$update->apply_update();

		add_action( 'admin_init', array( $this, 'redirect_after_update' ) );
	}

	public function redirect_after_update() {
		wp_redirect( remove_query_arg( 'ac_do_update' ) );
		exit;
	}

	public function show_update_notice() {
		$url = add_query_arg( array( 'ac_do_update' => 'true' ), AC()->admin()->get_settings_url() );

		$message = sprintf( '<strong>%s</strong> &ndash; %s <a href="%s" class="button ac-update-now">%s</a>',
			esc_html__( 'Admin Columns', 'codepress-admin-columns' ),
			esc_html__( 'We need to update your database to the latest version.', 'codepress-admin-columns' ),
			esc_url( $url ),
			esc_html__( 'Run the updater', 'codepress-admin-columns' )
		);

		AC()->notice( $message, 'notice-info' );
	}

}