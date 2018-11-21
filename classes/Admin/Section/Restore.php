<?php
namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\Capabilities;
use AC\ListScreen;
use AC\Message;

class Restore extends Section {

	public function __construct() {
		parent::__construct( 'restore', __( 'Restore Settings', 'codepress-admin-columns' ), __( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ) );
	}

	public function register() {
		add_action( 'admin_init', array( $this, 'handle_request' ) );
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	private function verify_nonce( $action ) {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $action );
	}

	/**
	 * Deletes all stored column settings. Does not delete general settings.
	 */
	private function delete_all_column_settings() {
		global $wpdb;

		$sql = "
			DELETE
			FROM $wpdb->options
			WHERE option_name LIKE %s";

		$wpdb->query( $wpdb->prepare( $sql, ListScreen::OPTIONS_KEY . '%' ) );

		// @since 3.0
		do_action( 'ac/restore_all_columns' );
	}

	public function handle_request() {
		if ( ! current_user_can( Capabilities::MANAGE ) ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'ac_action' ) ) {
			case 'restore_all' :
				if ( $this->verify_nonce( 'restore-all' ) ) {
					$this->delete_all_column_settings();

					$notice = new Message\Notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ) );
					$notice->register();
				}

				break;
		}
	}

	public function render() {
		?>
		<form method="post">

			<?php wp_nonce_field( 'restore-all', '_ac_nonce', false ); ?>

			<input type="hidden" name="ac_action" value="restore_all">
			<input type="submit" class="button" name="ac-restore-defaults" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
		</form>
		<?php
	}

}