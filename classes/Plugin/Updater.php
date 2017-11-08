<?php

class AC_Plugin_Updater {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * @var bool
	 */
	protected $first_install;

	/**
	 * @var bool
	 */
	protected $apply_updates;

	/**
	 * @var array
	 */
	protected $updates;

	protected function __construct() {
		$this->apply_updates = 'true' === filter_input( INPUT_GET, 'ac_do_update' );
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
	private function set_first_install() {
		global $wpdb;

		$sql = "
			SELECT option_id
			FROM {$wpdb->options}
			WHERE option_name LIKE 'cpac_options_%'
			LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		$this->first_install = empty( $results );
	}

	/**
	 * @return bool
	 */
	public function is_first_install() {
		if ( null === $this->first_install ) {
			$this->set_first_install();
		}

		return $this->first_install;
	}

	public function add_update( AC_Plugin_Update $update ) {
		$this->updates[ $update->get_basename() ][ $update->get_version() ] = $update;
	}

	/**
	 * @param AC_Plugin $plugin
	 * @param array     $updates Key should contain version, value should be an array with callbacks
	 */
	public function parse_updates() {
		ksort( $this->updates );

		foreach ( $this->updates as $basename => $updates ) {
			krsort( $this->updates[ $basename ], SORT_NUMERIC );

			/* @var AC_Plugin_Update $update */
			foreach ( $updates as $update ) {
				if ( $update->needs_update() ) {
					if ( ! $this->apply_updates ) {
						$this->show_update_notice();

						return;
					}

					$update->apply_update();
				}
			}
		}
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