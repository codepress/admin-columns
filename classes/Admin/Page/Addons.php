<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin\Page;
use AC\Message\Notice;
use AC\PluginInformation;

class Addons extends Page
	implements AC\Registrable {

	const NAME = 'addons';

	public function __construct() {
		parent::__construct( self::NAME, __( 'Add-ons', 'codepress-admin-columns' ) );
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		$this->handle_request();
		$this->handle_install_request();
		$this->page_notices();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	public function page_notices() {
		if ( ! current_user_can( AC\Capabilities::MANAGE ) ) {
			return;
		}

		if ( ! ac_is_pro_active() ) {
			$link = ac_helper()->html->link( ac_get_site_utm_url( false, 'addon' ), __( 'Admin Columns Pro', 'codepress-admin-columns' ), array( 'target' => '_blank' ) );

			$this->register_notice(
				sprintf( __( 'All add-ons require %s.', 'codepress-admin-columns' ), $link ),
				Notice::INFO
			);

			return;
		}

		foreach ( new AC\Integrations() as $integration ) {
			$plugin = new PluginInformation( $integration->get_basename() );

			if ( ! $plugin->is_active() || $integration->is_plugin_active() ) {
				continue;
			}

			$link = sprintf( '<a href="%s">%s</a>', $integration->get_plugin_link(), $integration->get_title() );

			$this->register_notice(
				sprintf( __( '%s needs to be installed and active for the add-on to work.', 'codepress-admin-columns' ), $link ),
				Notice::WARNING
			);
		}
	}

	/**
	 * Display an activation/deactivation message on the addons page if applicable
	 * @since 2.2
	 */
	public function handle_request() {
		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_ac_nonce' ), 'ac-plugin-status-change' ) ) {
			return;
		}

		switch ( filter_input( INPUT_GET, 'status' ) ) {
			case 'activate' :
				$this->show_activation_notice( filter_input( INPUT_GET, 'plugin' ) );

				break;
			case 'deactivate' :
				$this->show_deactivation_notice( filter_input( INPUT_GET, 'plugin' ) );

				break;
		}
	}

	/**
	 * @param string $slug Plugin dirname
	 */
	private function show_activation_notice( $slug ) {
		$integration = AC\IntegrationFactory::create_by_dirname( $slug );

		if ( ! $integration ) {
			return;
		}

		$plugin = new PluginInformation( $integration->get_basename() );

		$plugin_name = '<strong>' . sprintf( __( '%s add-on', 'codepress-admin-columns' ), $integration->get_title() ) . '</strong>';

		if ( $plugin->is_active() ) {
			$this->register_notice(
				sprintf( __( '%s successfully activated.', 'codepress-admin-columns' ), $plugin_name ),
				Notice::SUCCESS
			);

			return;
		}

		$this->register_notice(
			sprintf( __( '%s could not be activated.', 'codepress-admin-columns' ), $plugin_name ) . ' ' . sprintf( __( 'Please visit the %s page.', 'codepress-admin-columns' ), $this->get_plugins_link() ),
			Notice::ERROR
		);
	}

	/**
	 * @return string
	 */
	private function get_plugins_link() {
		return ac_helper()->html->link( admin_url( 'plugins.php' ), strtolower( __( 'Plugins' ) ) );
	}

	/**
	 * @param string $slug Plugin dirname
	 */
	private function show_deactivation_notice( $slug ) {
		$integration = AC\IntegrationFactory::create_by_dirname( $slug );

		if ( ! $integration ) {
			return;
		}

		$this->register_notice(
			sprintf( __( '%s successfully deactivated.', 'codepress-admin-columns' ), '<strong>' . $integration->get_title() . '</strong>' )
		);
	}

	private function get_link() {
		return ac_get_admin_url( $this->get_slug() );
	}

	/**
	 * Admin scripts
	 */
	public function admin_scripts() {
		wp_enqueue_style( 'ac-admin-page-addons', AC()->get_url() . 'assets/css/admin-page-addons.css', array(), AC()->get_version() );
	}

	/**
	 * @param string $message
	 * @param string $type
	 */
	private function register_notice( $message, $type = '' ) {
		$notice = new Notice( $message );

		if ( $type ) {
			$notice->set_type( $type );
		}

		$notice->register();
	}

	/**
	 * Handles the installation of the add-on
	 * @since 2.2
	 */
	public function handle_install_request() {
		if ( ! wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), 'install-ac-addon' ) ) {
			return;
		}

		$dirname = filter_input( INPUT_GET, 'plugin' );

		if ( ! $dirname ) {
			return;
		}

		if ( ! ac_is_pro_active() ) {
			$this->register_notice( __( 'You need Admin Columns Pro.', 'codepress-admin-columns' ), Notice::ERROR );

			return;
		}

		$integration = AC\IntegrationFactory::create_by_dirname( $dirname );

		if ( ! $integration ) {
			$this->register_notice( __( 'Addon does not exist.', 'codepress-admin-columns' ), Notice::ERROR );

			return;
		}

		$error_message = apply_filters( 'ac/addons/install_request/maybe_error', false, $integration->get_slug() );

		if ( $error_message ) {
			$this->register_notice( $error_message, Notice::ERROR );

			return;
		}

		$install_url = add_query_arg( array(
			'action'      => 'install-plugin',
			'plugin'      => $integration->get_slug(),
			'ac-redirect' => true,
		), wp_nonce_url( self_admin_url( 'update.php' ), 'install-plugin_' . $integration->get_slug() ) );

		wp_redirect( $install_url );
		exit;
	}

	/**
	 * Addons are grouped into addon groups by providing the group an addon belongs to.
	 * @since 2.2
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

		if ( ! $groups ) {
			return false;
		}

		return $groups[ $name ];
	}

	/**
	 * @param string $basename
	 *
	 * @return PluginInformation
	 */
	private function get_plugin_info( $basename ) {
		return new PluginInformation( $basename );
	}

	/**
	 * Group a list of add-ons
	 * @since 3.0
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 */
	private function get_grouped_addons() {
		$active = array();
		$inactive = array();

		foreach ( new AC\Integrations() as $integration ) {
			if ( $this->get_plugin_info( $integration->get_basename() )->is_active() ) {
				$active[] = $integration;
			} else {
				$inactive[] = $integration;
			}
		}

		/* @var AC\Integration[] $sorted */
		$sorted = array_merge( $active, $inactive );

		$grouped = array();
		foreach ( $this->get_addon_groups() as $group => $label ) {
			foreach ( $sorted as $integration ) {
				$addon_group = 'default';

				if ( $this->get_plugin_info( $integration->get_basename() )->is_active() ) {
					$addon_group = 'recommended';
				}

				if ( $this->get_plugin_info( $integration->get_basename() )->is_installed() ) {
					$addon_group = 'installed';
				}

				if ( ! isset( $grouped[ $group ] ) ) {
					$grouped[ $group ]['title'] = $label;
				}

				if ( $addon_group === $group ) {
					$grouped[ $group ]['addons'][] = $integration;
				}
			}

			if ( empty( $grouped[ $group ]['addons'] ) ) {
				unset( $grouped[ $group ] );
			}
		}

		return $grouped;
	}

	/**
	 * Activate plugin
	 *
	 * @param $basename
	 *
	 * @return string
	 */
	private function get_activation_url( $basename ) {
		return $this->get_plugin_action_url( 'activate', $basename );
	}

	/**
	 * Deactivate plugin
	 *
	 * @param $basename
	 *
	 * @return string
	 */
	private function get_deactivation_url( $basename ) {
		return $this->get_plugin_action_url( 'deactivate', $basename );
	}

	/**
	 * Activate or Deactivate plugin
	 *
	 * @param string $action
	 * @param string $basename
	 *
	 * @return string
	 */
	private function get_plugin_action_url( $action, $basename ) {
		return add_query_arg( array(
			'action'      => $action,
			'plugin'      => $basename,
			'ac-redirect' => true,
		), wp_nonce_url( admin_url( 'plugins.php' ), $action . '-plugin_' . $basename ) );
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	private function get_plugin_install_url( $slug ) {
		return add_query_arg( array(
			'action' => 'install',
			'plugin' => $slug,
		), wp_nonce_url( $this->get_link(), 'install-ac-addon' ) );
	}

	/**
	 * @param AC\Integration $addon
	 *
	 * @return string
	 */
	private function render_actions( AC\Integration $addon ) {
		ob_start();

		// Installed..
		if ( $this->get_plugin_info( $addon->get_basename() )->is_installed() ) :

			// Active
			if ( $this->get_plugin_info( $addon->get_basename() )->is_active() ) : ?>
				<span class="active"><?php _e( 'Active', 'codepress-admin-columns' ); ?></span>

				<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
					<a href="<?php echo esc_url( $this->get_deactivation_url( $addon->get_basename() ) ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
				<?php endif;
			// Not active
			elseif ( current_user_can( 'activate_plugins' ) ) : ?>
				<a href="<?php echo esc_url( $this->get_activation_url( $addon->get_basename() ) ); ?>" class="button button-primary right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
			<?php endif;

		// Not installed...
		elseif ( ac_is_pro_active() && current_user_can( 'install_plugins' ) ) : ?>
			<a href="<?php echo esc_url( $this->get_plugin_install_url( $addon->get_slug() ) ); ?>" class="button">
				<?php esc_html_e( 'Download & Install', 'codepress-admin-columns' ); ?>
			</a>
		<?php else : ?>
			<a target="_blank" href="<?php echo esc_url( $addon->get_link() ); ?>" class="button"><?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
		<?php endif;

		return ob_get_clean();
	}

	/**
	 * @return void
	 */
	public function render() {
		foreach ( $this->get_grouped_addons() as $group_slug => $group ) :
			?>

			<div class="ac-addon group-<?php echo esc_attr( $group_slug ); ?>">
				<h2><?php echo esc_html( $group['title'] ); ?></h2>

				<ul>
					<?php
					foreach ( $group['addons'] as $addon ) {
						/* @var AC\Integration $addon */

						$view = new AC\View( array(
							'logo'        => AC()->get_url() . $addon->get_logo(),
							'title'       => $addon->get_title(),
							'description' => $addon->get_description(),
							'actions'     => $this->render_actions( $addon ),
						) );

						echo $view->set_template( 'admin/edit-addon' );
					}
					?>
				</ul>
			</div>
		<?php endforeach;
	}

}