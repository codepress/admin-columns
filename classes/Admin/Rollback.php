<?php

class AC_Admin_Rollback {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $backup_prefix;

	public function __construct() {
		$this->backup_prefix = 'ac_v3_backup_';
		$this->id = 'rollback-to-v3';
		$this->label = __( 'Rollback', 'codepress-admin-columns' );
		$this->description = __( 'Rollback to the previous version of Admin Columns.', 'codepress-admin-columns' );

		// show only when Admin Columns is version 4
		if ( ac_is_version_gte( 4 ) ) {
			add_filter( 'ac/settings/groups', array( $this, 'register' ) );
			add_filter( 'ac/settings/group/' . $this->id, array( $this, 'display' ) );
			add_action( 'admin_init', array( $this, 'request' ) );

			if ( ! $this->backup_done() ) {
				$this->backup();
			}
		}
	}

	public function display() {
		$warning = __( "Warning! All changes after upgrading to version 4 will be lost. 'OK' to rollback, 'Cancel' to stop", 'codepress-admin-columns' );

		?>

		<form action="" method="post">
			<?php wp_nonce_field( $this->id, '_cpac_nonce', false ) ?>

			<input type="hidden" name="cpac_action" value="<?php echo $this->id; ?>">
			<input type="submit" class="button" value="<?php echo esc_attr( __( 'Rollback to version 3', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( $warning ); ?>');">
		</form>

		<?php
	}

	protected function backup_done() {
		return get_option( 'ac_backup_v3' );
	}

	protected function backup() {
		global $wpdb;

		$sql = "
			SELECT * 
			FROM $wpdb->options
			WHERE option_name LIKE %s
		";

		$results = $wpdb->get_results( $wpdb->prepare( $sql, ACP_Layouts::LAYOUT_KEY . '%' ) );

		if ( ! is_array( $results ) ) {
			return;
		}

		foreach( $results as $result ) {
			add_option( $this->backup_prefix . $result->option_name, $result->option_value, '', false );
		}

		$sql = "
			SELECT *
			FROM $wpdb->options
			WHERE option_name LIKE %s";

		$results = $wpdb->get_row( $wpdb->prepare( $sql, AC_ListScreen::OPTIONS_KEY . '%' ) );

		if ( ! is_array( $results ) ) {
			return;
		}

		foreach( $results as $result ) {
			add_option( $this->backup_prefix . $result->option_name, $result->option_value, '', false );
		}
	}

	public function request() {
		$nonce = filter_input( INPUT_POST, '_cpac_nonce' );
		$action = filter_input( INPUT_POST, 'cpac_action' );

		if ( $this->id !== $action || ! wp_verify_nonce( $nonce, $this->id ) || ! AC()->user_can_manage_admin_columns() ) {
			return;
		}

		// do rollback
	}

	public function register( $groups ) {
		$groups['rollback'] = array(
			'title'       => $this->label,
			'description' => $this->description,
		);

		return $groups;
	}

}