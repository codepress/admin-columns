<?php
namespace AC\Admin\Section;

use AC\ListScreen;
use AC\Message;

class Restore extends Custom {

	public function __construct() {
		parent::__construct(
			'restore',
			__( 'Restore Settings', 'codepress-admin-columns' ),
			__( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' )
		);
	}

	public function register() {
		add_action( 'admin_init', array( $this, 'request' ) );
	}

	public function request() {
		global $wpdb;

		if ( ! $this->validate_request() ) {
			return;
		}

		$sql = "
			DELETE
			FROM $wpdb->options
			WHERE option_name LIKE %s";

		$wpdb->query( $wpdb->prepare( $sql, ListScreen::OPTIONS_KEY . '%' ) );

		do_action( 'ac/restore_all_columns' );

		$notice = new Message\Notice( __( 'Default settings successfully restored.', 'codepress-admin-columns' ) );
		$notice->register();
	}

	protected function display_fields() {
		?>
		<form method="post">

			<?php
			$this->nonce_field();
			$this->action_field();
			?>

			<input type="submit" class="button" name="ac-restore-defaults" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
		</form>
		<?php
	}

}