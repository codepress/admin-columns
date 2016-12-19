<?php

class AC_Admin_Tab_Settings extends AC_Admin_Tab {

	CONST SETTINGS_KEY = 'cpac_general_options';

	private $options;

	public function __construct() {
		$this
			->set_slug( 'settings' )
			->set_label( __( 'Settings', 'codepress-admin-columns' ) );

		$this->options = get_option( self::SETTINGS_KEY );

		register_setting( 'cpac-general-settings', self::SETTINGS_KEY );

		// Requests
		add_action( 'admin_init', array( $this, 'handle_column_request' ) );
	}

	public function attr_name( $key ) {
		echo esc_attr( self::SETTINGS_KEY . '[' . $key . ']' );
	}

	/**
	 * @param $key
	 *
	 * @return false|string When '0' there are no options stored.
	 */
	public function get_option( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : false;
	}

	private function is_empty_options() {
		return false === $this->options;
	}

	public function delete_options() {
		delete_option( self::SETTINGS_KEY );
	}

	/**
	 * @return bool
	 */
	public function show_edit_button() {
		return $this->is_empty_options() || $this->get_option( 'show_edit_button' );
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {

		if ( ! current_user_can( 'manage_admin_columns' ) || ! $this->is_current_screen() ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'cpac_action' ) ) :
			case 'restore_all' :
				if ( wp_verify_nonce( filter_input( INPUT_POST, '_cpac_nonce' ), 'restore-all' ) ) {

					// todo: make this non static? There is no reason why the list screen should be absent here?
					AC_Settings_ListScreen::delete_all_settings();

					cpac_admin_message( __( 'Default settings succesfully restored.', 'codepress-admin-columns' ), 'updated' );

					// @since NEWVERSION
					do_action( 'ac/restore_all_columns' );
				}
				break;

		endswitch;
	}

	public function single_checkbox( $args = array() ) {
		$defaults = array(
			'name'          => '',
			'label'         => '',
			'instructions'  => '',
			'default_value' => false,
		);

		$args = (object) wp_parse_args( $args, $defaults );

		$current_value = $this->is_empty_options() ? $args->default_value : $this->get_option( $args->name );
		?>
		<p>
			<label for="<?php echo $args->name; ?>">
				<input name="<?php $this->attr_name( $args->name ); ?>" id="<?php echo $args->name; ?>" type="checkbox" value="1" <?php checked( $current_value, '1' ); ?>>
				<?php echo $args->label; ?>
			</label>
			<?php if ( $args->instructions ) : ?>
				<a href="javascript:;" class="cpac-pointer" rel="pointer-<?php echo $args->name; ?>" data-pos="right">
					<?php _e( 'Instructions', 'codepress-admin-columns' ); ?>
				</a>
			<?php endif; ?>
		</p>
		<?php if ( $args->instructions ) : ?>
			<div id="pointer-<?php echo $args->name; ?>" style="display:none;">
				<h3><?php _e( 'Notice', 'codepress-admin-columns' ); ?></h3>
				<?php echo $args->instructions; ?>
			</div>
			<?php
		endif;

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

							<?php
							$this->single_checkbox( array(
								'name'          => 'show_edit_button',
								'label'         => __( "Show \"Edit Columns\" button on admin screens. Default is <code>on</code>.", 'codepress-admin-columns' ),
								'default_value' => '1',
							) );
							?>

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