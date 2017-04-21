<?php

class AC_Admin_Page_Settings extends AC_Admin_Page {

	const SETTINGS_NAME = 'cpac_general_options';

	const SETTINGS_GROUP = 'cpac-general-settings';

	private $options;

	public function __construct() {
		$this
			->set_slug( 'settings' )
			->set_label( __( 'Settings', 'codepress-admin-columns' ) );

		$this->options = get_option( self::SETTINGS_NAME );

		register_setting( self::SETTINGS_GROUP, self::SETTINGS_NAME );

		add_filter( 'option_page_capability_' . self::SETTINGS_GROUP, array( $this, 'set_capability' ) );
		add_action( 'admin_init', array( $this, 'handle_column_request' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function admin_scripts() {
	    if ( $this->is_current_screen() ) {
		    wp_enqueue_style( 'ac-admin-page-settings', AC()->get_plugin_url() . 'assets/css/admin-page-settings' . AC()->minified() . '.css', array(), AC()->get_version() );
	    }
	}

	public function set_capability() {
		return 'manage_admin_columns';
	}

	/**
	 * @param string $key
	 */
	public function attr_name( $key ) {
		echo esc_attr( self::SETTINGS_NAME . '[' . sanitize_key( $key ) . ']' );
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
		delete_option( self::SETTINGS_NAME );
	}

	/**
	 * @return bool
	 */
	public function show_edit_button() {
		return $this->is_empty_options() || $this->get_option( 'show_edit_button' );
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

		$wpdb->query( $wpdb->prepare( $sql, AC_ListScreen::OPTIONS_KEY . '%' ) );

		// @since 3.0
		do_action( 'ac/restore_all_columns' );
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {
		if ( ! AC()->user_can_manage_admin_columns() || ! $this->is_current_screen() ) {
			return;
		}

		switch ( filter_input( INPUT_POST, 'ac_action' ) ) :

			case 'restore_all' :
				if ( $this->verify_nonce( 'restore-all' ) ) {
					$this->delete_all_column_settings();

					AC()->notice( __( 'Default settings succesfully restored.', 'codepress-admin-columns' ), 'updated' );
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
                <a class="ac-pointer instructions" rel="pointer-<?php echo $args->name; ?>" data-pos="right">
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
        <table class="form-table ac-form-table settings">
            <tbody>
            <tr class="general">
                <th scope="row">
                    <h2><?php _e( 'General Settings', 'codepress-admin-columns' ); ?></h2>
                    <p><?php _e( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ); ?></p>
                </th>
                <td>
                    <form method="post" action="options.php">

						<?php settings_fields( self::SETTINGS_GROUP ); ?>

						<?php
						$this->single_checkbox( array(
							'name'          => 'show_edit_button',
							'label'         => __( "Show \"Edit Columns\" button on admin screens. Default is <code>on</code>.", 'codepress-admin-columns' ),
							'default_value' => '1',
						) );
						?>

						<?php do_action( 'ac/settings/general', $this ); ?>

                        <p>
                            <input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
                        </p>
                    </form>
                </td>
            </tr>

			<?php

			/** Allow plugins to add their own custom settings to the settings page. */
			if ( $groups = apply_filters( 'ac/settings/groups', array() ) ) {

				foreach ( $groups as $id => $group ) {

					$title = isset( $group['title'] ) ? $group['title'] : '';
					$description = isset( $group['description'] ) ? $group['description'] : '';

					?>

                    <tr>
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