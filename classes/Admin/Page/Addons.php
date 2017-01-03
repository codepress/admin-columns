<?php

class AC_Admin_Page_Addons extends AC_Admin_Page {

	public function __construct() {
		$this
			->set_slug( 'addons' )
			->set_label( __( 'Add-ons', 'codepress-admin-columns' ) );

		add_action( 'cpac_messages', array( $this, 'maybe_display_addon_statuschange_message' ) );
	}

	/**
	 * Display an activation/deactivation message on the addons page if applicable
	 *
	 * @since 2.2
	 */
	public function maybe_display_addon_statuschange_message() {
		$message = false;

		if ( ! empty( $_REQUEST['activate'] ) ) {
			$message = __( 'Add-on successfully activated.', 'codepress-admin-columns' );
		}
		else if ( ! empty( $_REQUEST['deactivate'] ) ) {
			$message = __( 'Add-on successfully deactivated.', 'codepress-admin-columns' );
		}

		if ( $message ) : ?>
            <div class="updated cac-notification below-h2">
                <p><?php echo $message; ?></p>
            </div>
			<?php
		endif;
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-tab-addons', AC()->get_plugin_url() . 'assets/css/admin-tab-addons' . AC()->minified() . '.css', array(), AC()->get_version(), 'all' );
	}

	public function display() {
		$addon_groups = AC()->addons()->get_addon_groups();
		$grouped_addons = AC()->addons()->get_available_addons( true );
		?>
		<?php foreach ( $grouped_addons as $group_name => $addons ) : ?>
            <h2><?php echo $addon_groups[ $group_name ]; ?></h2>

            <ul class="cpac-addons">
				<?php
				foreach ( $addons as $addon ) :
					/* @var AC_Addon $addon */ ?>
                    <li>
                        <div class="cpac-addon-content">
                            <div class="inner">
							<?php if ( $addon->get_image_url() ) : ?>
                                <img src="<?php echo esc_attr( $addon->get_image_url() ); ?>"/>
							<?php else : ?>
                                <h2><?php echo esc_html( $addon->get_title() ); ?></h2>
							<?php endif; ?>
                            </div>
                        </div>
                        <div class="cpac-addon-header">
                            <h3><?php echo esc_html( $addon->get_title() ); ?></h3>
                            <p><?php echo esc_html( $addon->get_description() ); ?></p>
                        </div>
                        <div class="cpac-addon-actions">
							<?php

							// Installed..
							if ( $plugin_basename = AC()->addons()->get_installed_addon_plugin_basename( $addon->get_slug() ) ) : ?>
								<?php if ( is_plugin_active( $plugin_basename ) ) : ?>
									<?php $deactivation_url = wp_nonce_url( add_query_arg( array( 'action' => 'deactivate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $plugin_basename ); ?>
                                    <a href="#" class="button button-disabled cpac-installed"><?php _e( 'Active', 'codepress-admin-columns' ); ?></a>
                                    <a href="<?php echo esc_url( $deactivation_url ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
								<?php else : ?>
									<?php $activation_url = wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $plugin_basename ); ?>
                                    <a href="#" class="button button-disabled cpac-installed"><?php _e( 'Installed', 'codepress-admin-columns' ); ?></a>
                                    <a href="<?php echo esc_url( $activation_url ); ?>" class="button right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<?php

							// Not installed...
							else :

								if ( ac_is_pro_active() ) :
									$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install', 'plugin' => $addon->get_slug() ), AC()->admin()->get_link( 'addons' ) ), 'install-cac-addon' );
									?>
                                    <a href="<?php echo esc_url( $install_url ); ?>" class="button"><?php esc_html_e( 'Download & Install', 'codepress-admin-columns' ); ?></a>
								<?php else : ?>
                                    <a target="_blank" href="<?php echo esc_url( $addon->get_link() ); ?>" class="button"><?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
								<?php endif;
							endif;
							?>
                        </div>
                    </li>
				<?php endforeach; // addons ?>
            </ul>
		<?php endforeach; // grouped_addons
	}

}