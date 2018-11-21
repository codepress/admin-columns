<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\Page;
use AC\Capabilities;
use AC\ListScreen;
use AC\Message;

class Settings extends Page {

	public function __construct() {
		parent::__construct( 'settings', __( 'Settings', 'codepress-admin-columns' ) );
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		add_action( 'admin_init', array( $this, 'handle_column_request' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-settings', AC()->get_url() . 'assets/css/admin-page-settings.css', array(), AC()->get_version() );
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
	 * Nonce Field
	 *
	 * @param string $action
	 */
	private function nonce_field( $action ) {
		wp_nonce_field( $action, '_ac_nonce', false );
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

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {
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

	public function display() { ?>
		<table class="form-table ac-form-table settings">
			<tbody>
			<tr class="general">
				<th scope="row">
					<h2><?php _e( 'General Settings', 'codepress-admin-columns' ); ?></h2>
					<p><?php _e( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td>
					<form method="post" action="options.php">

						<?php settings_fields( AC\Settings\General::SETTINGS_GROUP ); ?>

						<?php
						foreach ( AC\Settings::get_settings() as $setting ) {
							echo sprintf( '<p>%s</p>', $setting->render() );
						}
						?>

						<p>
							<input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
						</p>
					</form>
				</td>
			</tr>

			<?php

			// todo

			/** Allow plugins to add their own custom settings to the settings page. */
			if ( $groups = apply_filters( 'ac/settings/groups', array() ) ) {

				foreach ( $groups as $id => $group ) {

					$title = isset( $group['title'] ) ? $group['title'] : '';
					$description = isset( $group['description'] ) ? $group['description'] : '';
					$attr_id = isset( $group['id'] ) ? $group['id'] : '';

					?>

					<tr id="<?php echo esc_attr( $attr_id ); ?>">
						<th scope="row">
							<h2><?php echo esc_html( $title ); ?></h2>
							<p><?php echo $description; ?></p>
						</th>
						<td>
							<?php

							/** Use this Hook to add additional fields to the group */
							do_action( "ac/settings/group/" . $id );

							?>
						</td>
					</tr>

					<?php
				}
			}
			?>

			<tr class="restore">
				<th scope="row">
					<h2><?php _e( 'Restore Settings', 'codepress-admin-columns' ); ?></h2>
					<p><?php _e( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td>
					<form method="post">

						<?php $this->nonce_field( 'restore-all' ); ?>

						<input type="hidden" name="ac_action" value="restore_all">
						<input type="submit" class="button" name="ac-restore-defaults" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
					</form>
				</td>
			</tr>

			</tbody>
		</table>

		<?php
	}

}