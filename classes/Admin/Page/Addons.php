<?php

class AC_Admin_Page_Addons extends AC_Admin_Page {

	public function __construct() {
		$this
			->set_slug( 'addons' )
			->set_label( __( 'Add-ons', 'codepress-admin-columns' ) );

		add_action( 'admin_init', array( $this, 'handle_request' ) );
		add_action( 'admin_init', array( $this, 'handle_install_request' ) );
		add_action( 'admin_init', array( $this, 'notice_missing' ) );
		add_action( 'admin_init', array( $this, 'notice_nudge' ) );
		add_filter( 'wp_redirect', array( $this, 'redirect_after_status_change' ) );

		add_action( 'wp_ajax_cpac_hide_install_addons_notice', array( $this, 'ajax_hide_install_addons_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Notice for missing addons
	 */
	public function notice_missing() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		if ( ! current_user_can( 'manage_admin_columns' ) || ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$message = false;

		if ( ac_is_pro_active() ) {
			foreach ( AC()->addons()->get_active_addons() as $addon ) {

				if ( ! $addon->is_plugin_installed() && ! $addon->is_plugin_active() ) {

					// Addon is active. Plugin is not installed and not active.
					$message = sprintf( __( '%s plugin needs to be installed for the add-on to work.', 'codepress-admin-columns' ), ac_helper()->html->link( $addon->get_plugin_url(), $addon->get_title(), array( 'target' => '_blank' ) ) );
				} else if ( ! $addon->is_plugin_active() ) {

					// Addon is active. Plugin is installed, but not active.
					$message = sprintf( __( '%s plugin is installed, but not active.', 'codepress-admin-columns' ), '<strong>' . $addon->get_plugin()->get_plugin_var( 'Name' ) . '</strong>' );

					if ( current_user_can( 'activate_plugins' ) ) {
						$message .= ' ' . sprintf( __( 'Click %s to activate the plugin.', 'codepress-admin-columns' ), ac_helper()->html->link( $addon->get_plugin_activation_url(), __( 'here', 'codepress-admin=n-columns' ) ) );
					}
				}
			}
		} else {
			$titles = array();

			foreach ( AC()->addons()->get_active_addons() as $addon ) {
				$titles[] = '<strong>' . esc_html( $addon->get_title() ) . '</strong>';
			}

			if ( $titles ) {
				$message = sprintf( _n( '%s add-on requires %s.', '%s add-ons requires %s.', count( $titles ), 'codepress-admin-columns' ), ac_helper()->string->enumeration_list( $titles, 'and' ), ac_helper()->html->link( ac_get_site_url(), __( 'Admin Columns Pro', 'codepress-admin-columns' ), array( 'target' => '_blank' ) ) );
			}
		}

		if ( ! $message ) {
			return;
		}

		AC()->notices()->register( new AC_Notice_Simple( $message, 'notice notice-warning' ) );
	}

	/**
	 * Nudge for integrations
	 */
	public function notice_nudge() {
		if ( $this->is_current_screen() ) {
			return;
		}

		if ( ! current_user_can( 'manage_admin_columns' ) || ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( AC()->suppress_site_wide_notices() ) {
			return;
		}

		$addons = array();

		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( $addon->show_missing_notice_on_current_page() && $addon->is_plugin_active() && ! $addon->is_active() ) {
				$addons[] = $addon;
			}
		}

		$titles = array();

		foreach ( $addons as $addon ) {
			$titles[] = '<strong>' . $addon->get_title() . '</strong>';
		}

		if ( ! $titles ) {
			return;
		}

		ob_start();
		?>
		<a href="#" class="hide-notice hide-install-addons-notice"></a>
		<p>
			<?php printf( __( "Did you know Admin Columns Pro has an integration addon for %s? With the proper Admin Columns Pro license, you can download them from %s!", 'codepress-admin-columns' ), ac_helper()->string->enumeration_list( $titles, 'and' ), ac_helper()->html->link( $this->get_link(), __( 'the addons page', 'codepress-admin-columns' ) ) ); ?>
		</p>
		<?php

		$message = ob_get_clean();

		// TODO: handle ajax callback. General dismiss callback?

		// return (bool) get_user_meta( get_current_user_id(), 'ac_hide_notice_addons', true );
		// update_user_meta( get_current_user_id(), 'ac_hide_notice_addons', true );

		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . "assets/css/message.css", array(), AC()->get_version() );
		wp_enqueue_script( 'ac-notice-addon', AC()->get_plugin_url() . "assets/js/message-addon.js", array( 'jquery' ), AC()->get_version() );

		AC()->notices()->register( new AC_Notice_Simple( $message, 'notice notice-warning' ) );
	}

	/**
	 * Display an activation/deactivation message on the addons page if applicable
	 *
	 * @since 2.2
	 */
	public function handle_request() {
		if ( ! $this->is_current_screen() ) {
			return;
		}

		$basename = filter_input( INPUT_GET, 'plugin' );

		if ( ! $basename ) {
			return;
		}

		$status = filter_input( INPUT_GET, 'status' );

		if ( ! $status ) {
			return;
		}

		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_ac_nonce' ), 'ac-plugin-status-change' ) ) {
			return;
		}

		$plugin = new AC_PluginInformation( dirname( $basename ) );

		$activate_string = __( '%s plugin successfully activated.', 'codepress-admin-columns' );
		$deactivate_string = __( '%s plugin successfully deactivated.', 'codepress-admin-columns' );

		// Is plugin an addon?
		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( $addon->get_basename() === $plugin->get_basename() ) {
				$activate_string = __( '%s successfully activated.', 'codepress-admin-columns' );
				$deactivate_string = __( '%s successfully deactivated.', 'codepress-admin-columns' );
			}
		}

		switch ( $status ) {
			case 'activate' :
				if ( $plugin->is_active() ) {
					AC()->notices()->register( new AC_Notice_Simple( sprintf( $activate_string, '<strong>' . $plugin->get_name() . '</strong>' ) ) );
				} else {
					AC()->notices()->register( new AC_Notice_Simple( sprintf( __( '%s could not be activated.', 'codepress-admin-columns' ), '<strong>' . $plugin->get_name() . '</strong>' ) . ' ' . sprintf( __( 'Please visit the %s page.', 'codepress-admin-columns' ), ac_helper()->html->link( admin_url( 'plugins.php' ), strtolower( __( 'Plugins' ) ) ) ), 'error' ) );
				}
				break;
			case 'deactivate' :
				AC()->notices()->register( new AC_Notice_Simple( sprintf( $deactivate_string, '<strong>' . $plugin->get_name() . '</strong>' ) ) );
				break;
		}
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		if ( $this->is_current_screen() ) {
			wp_enqueue_style( 'ac-admin-page-addons', AC()->get_plugin_url() . 'assets/css/admin-page-addons.css', array(), AC()->get_version() );
		}
	}

	/**
	 * @return bool
	 */
	// TODO
	private function hide_notice() {
		return (bool) get_user_meta( get_current_user_id(), 'ac_hide_notice_addons', true );
	}

	/**
	 * Ajax callback for hiding the "Missing addons" notice used for notifying users of available integration addons for plugins they have installed
	 *
	 * @since 2.4.9
	 */
	// TODO
	public function ajax_hide_install_addons_notice() {
		update_user_meta( get_current_user_id(), 'ac_hide_notice_addons', true );
	}

	/**
	 * Handles the installation of the add-on
	 *
	 * @since 2.2
	 */
	public function handle_install_request() {
		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), 'install-ac-addon' ) ) {
			return;
		}

		$addon = AC()->addons()->get_addon( filter_input( INPUT_GET, 'plugin' ) );

		if ( ! $addon ) {
			AC()->notices()->register( new AC_Notice_Simple( __( 'Addon does not exist.', 'codepress-admin-columns' ), 'error' ) );

			return;
		}

		if ( ! ac_is_pro_active() ) {
			AC()->notices()->register( new AC_Notice_Simple( __( 'You need Admin Columns Pro.', 'codepress-admin-columns' ), 'error' ) );

			return;
		}

		// Hook: trigger possible warning message before running WP installer ( api errors etc. )
		if ( $error = apply_filters( 'ac/addons/install_request/maybe_error', false, $_GET['plugin'] ) ) {
			AC()->notices()->register( new AC_Notice_Simple( $error, 'error' ) );

			return;
		}

		$install_url = add_query_arg( array(
			'action'      => 'install-plugin',
			'plugin'      => $addon->get_slug(),
			'ac-redirect' => true,
		), wp_nonce_url( network_admin_url( 'update.php' ), 'install-plugin_' . $addon->get_slug() ) );

		wp_redirect( $install_url );
		exit;
	}

	/**
	 * Redirect the user to the Admin Columns add-ons page after activation/deactivation of an add-on from the add-ons page
	 *
	 * @since 2.2
	 */
	public function redirect_after_status_change( $location ) {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow || ! is_admin() || ! filter_input( INPUT_GET, 'ac-redirect' ) || filter_input( INPUT_GET, 'error' ) ) {
			return $location;
		}

		$status = filter_input( INPUT_GET, 'action' );

		if ( ! $status ) {
			return $location;
		}

		$addon = false;

		// Check if either the addon is installed or it's plugin
		foreach ( AC()->addons()->get_addons() as $_addon ) {
			if ( in_array( filter_input( INPUT_GET, 'plugin' ), array( $_addon->get_basename(), $_addon->get_plugin_basename() ) ) ) {
				$addon = $_addon;
			}
		}

		if ( ! $addon ) {
			return $location;
		}

		$location = add_query_arg( array(
			'status'    => $status,
			'plugin'    => filter_input( INPUT_GET, 'plugin' ),
			'_ac_nonce' => wp_create_nonce( 'ac-plugin-status-change' ),
		), $this->get_link() );

		return $location;
	}

	/**
	 * Addons are grouped into addon groups by providing the group an addon belongs to.
	 *
	 * @since 2.2
	 *
	 * @return array Available addon groups ([group_name] => [label])
	 */
	public function get_addon_groups() {
		$addon_groups = array(
			'installed'   => __( 'Installed', 'codepress-admin-columns' ),
			'recommended' => __( 'Recommended', 'codepress-admin-columns' ),
			'default'     => __( 'Available', 'codepress-admin-columns' ),
		);

		/**
		 * Filter the addon groups
		 *
		 * @since 2.2
		 *
		 * @param array $addon_groups Available addon groups ([group_name] => [label])
		 */
		return apply_filters( 'ac/addons/groups', $addon_groups );
	}

	/**
	 * @param string $name
	 *
	 * @return string|false
	 */
	public function get_group( $name ) {
		$groups = $this->get_addon_groups();

		if ( ! isset( $groups ) ) {
			return false;
		}

		return $groups[ $name ];
	}

	/**
	 * Group a list of add-ons
	 *
	 * @since 3.0
	 *
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 */
	private function get_grouped_addons() {
		$active = array();
		$inactive = array();

		foreach ( AC()->addons()->get_addons() as $addon ) {
			if ( $addon->is_active() ) {
				$active[] = $addon;
			} else {
				$inactive[] = $addon;
			}
		}

		/* @var AC_Admin_Addon[] $sorted */
		$sorted = array_merge( $active, $inactive );

		$grouped = array();
		foreach ( $this->get_addon_groups() as $group => $label ) {
			foreach ( $sorted as $addon ) {
				$addon_group = 'default';

				if ( $addon->is_plugin_active() ) {
					$addon_group = 'recommended';
				}

				if ( $addon->is_installed() ) {
					$addon_group = 'installed';
				}

				if ( ! isset( $grouped[ $group ] ) ) {
					$grouped[ $group ]['title'] = $label;
				}

				if ( $addon_group === $group ) {
					$grouped[ $group ]['addons'][] = $addon;
				}
			}

			if ( empty( $grouped[ $group ]['addons'] ) ) {
				unset( $grouped[ $group ] );
			}
		}

		return $grouped;
	}

	public function display() {

		foreach ( $this->get_grouped_addons() as $group_slug => $group ) : ?>
			<div class="ac-addon group-<?php echo esc_attr( $group_slug ); ?>">
				<h2><?php echo esc_html( $group['title'] ); ?></h2>

				<ul>
					<?php
					foreach ( $group['addons'] as $addon ) :
						/* @var AC_Admin_Addon $addon */ ?>
						<li class="<?php echo esc_attr( $addon->get_slug() ); ?>">
							<div class="addon-header">
								<div class="inner">
									<?php if ( $addon->get_logo() ) : ?>
										<img src="<?php echo esc_attr( $addon->get_logo() ); ?>"/>
									<?php else : ?>
										<h2><?php echo esc_html( $addon->get_title() ); ?></h2>
									<?php endif; ?>
								</div>
							</div>
							<div class="addon-content">
								<h3><?php echo esc_html( $addon->get_title() ); ?></h3>
								<p><?php echo esc_html( $addon->get_description() ); ?></p>
							</div>
							<div class="addon-actions">
								<?php

								// Installed..
								if ( $addon->is_installed() ) :

									// Active
									if ( $addon->is_active() ) : ?>
										<span class="active"><?php _e( 'Active', 'codepress-admin-columns' ); ?></span>

										<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
											<a href="<?php echo esc_url( $addon->get_deactivation_url( $addon->get_basename() ) ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
										<?php endif;
									// Installed
									elseif ( current_user_can( 'activate_plugins' ) ) : ?>
										<a href="<?php echo esc_url( $addon->get_activation_url( $addon->get_basename() ) ); ?>" class="button button-primary right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
									<?php endif;

								// Not installed...
								else :
									if ( ac_is_pro_active() && current_user_can( 'install_plugins' ) ) : ?>
										<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'action' => 'install', 'plugin' => $addon->get_slug() ), $this->get_link() ), 'install-ac-addon' ) ); ?>" class="button"><?php esc_html_e( 'Download & Install', 'codepress-admin-columns' ); ?></a>
									<?php else : ?>
										<a target="_blank" href="<?php echo esc_url( $addon->get_link() ); ?>" class="button"><?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
									<?php endif;
								endif;
								?>
							</div>
						</li>
					<?php endforeach; // addons ?>
				</ul>
			</div>
		<?php endforeach; // grouped_addons
	}

}