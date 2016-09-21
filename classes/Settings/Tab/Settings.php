<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Tab_Settings extends AC_Settings_TabAbstract {

	CONST SETTINGS_KEY = 'cpac_general_options';

	private $options;

	public function __construct() {
		$this
			->set_slug( 'settings' )
			->set_label( __( 'Settings', 'codepress-admin-columns' ) );

		register_setting( 'cpac-general-settings', self::SETTINGS_KEY );
	}

	public function attr_name( $key ) {
		echo esc_attr( self::SETTINGS_KEY . '[' . $key . ']' );
	}

	public function get_option( $key ) {
		$options = $this->get_options();

		return isset( $options[ $key ] ) ? $options[ $key ] : false;
	}

	public function get_options() {
		if ( null === $this->options ) {
			$this->options = get_option( self::SETTINGS_KEY );
		}

		return $this->options;
	}

	public function display() { ?>
		<table class="form-table cpac-form-table settings">
			<tbody>
			<tr class="general">
				<th scope="row">
					<h3><?php _e( 'General Settings', 'codepress-admin-columns' ); ?></h3>
					<p><?php _e( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td class="padding-22">
					<div class="cpac_general">
						<form method="post" action="options.php">
							<?php settings_fields( 'cpac-general-settings' ); ?>
							<p>
								<label for="show_edit_button">
									<input name="<?php $this->attr_name( 'show_edit_button' ); ?>" type="hidden" value="0">
									<input name="<?php $this->attr_name( 'show_edit_button' ); ?>" id="show_edit_button" type="checkbox" value="1" <?php checked( $this->get_option( 'show_edit_button' ), '1' ); ?>>
									<?php _e( "Show \"Edit Columns\" button on admin screens. Default is <code>on</code>.", 'codepress-admin-columns' ); ?>
								</label>
							</p>

							<?php do_action( 'cac/settings/general', $this ); ?>

							<p>
								<input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
							</p>
						</form>
					</div>
				</td>
			</tr>

			<?php

			/** Allow plugins to add their own custom settings to the settings page. */
			if ( $groups = apply_filters( 'cac/settings/groups', array() ) ) {

				foreach ( $groups as $id => $group ) {

					$title = isset( $group['title'] ) ? $group['title'] : '';
					$description = isset( $group['description'] ) ? $group['description'] : '';

					?>

					<tr>
						<th scope="row">
							<h3><?php echo esc_html( $title ); ?></h3>

							<p><?php echo $description; ?></p>
						</th>
						<td class="padding-22">
							<?php

							/** Use this Hook to add additional fields to the group */
							do_action( "cac/settings/groups/row=" . $id );

							?>
						</td>
					</tr>

					<?php
				}
			}
			?>

			<tr class="restore">
				<th scope="row">
					<h3><?php _e( 'Restore Settings', 'codepress-admin-columns' ); ?></h3>
					<p><?php _e( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td class="padding-22">
					<form method="post">
						<?php wp_nonce_field( 'restore-all', '_cpac_nonce' ); ?>
						<input type="hidden" name="cpac_action" value="restore_all">
						<input type="submit" class="button" name="cpac-restore-defaults" value="<?php echo esc_attr( __( 'Restore default settings', 'codepress-admin-columns' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ) ); ?>');">
					</form>
				</td>
			</tr>

			</tbody>
		</table>

		<?php
	}

}