<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin;
use AC\Admin\Page;
use AC\Asset\Assets;
use AC\Asset\Localizable;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\PluginInformation;

// todo: Assets from __Admin/Page/Addons. remove Localizable
class Addons extends Page implements AC\Asset\Enqueueables, Localizable {

	const NAME = 'addons';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	public function __construct( Location\Absolute $location ) {
		parent::__construct( self::NAME, __( 'Add-ons', 'codepress-admin-columns' ) );

		$this->location = $location;
	}

	public function get_assets() {
		return new Assets( [
			new Style( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/css/admin-page-addons.css' ) ),
			new Admin\Asset\Addons( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/js/admin-page-addons.js' ) ),
		] );
	}

	public function localize() {
		wp_localize_script( 'ac-admin-page-addons', 'AC', [
			'ajax_nonce' => wp_create_nonce( 'ac-ajax' ),
		] );
	}

	public function render() {
		ob_start();

		foreach ( $this->get_grouped_addons() as $group_slug => $group ) :
			?>

			<div class="ac-addons group-<?php echo esc_attr( $group_slug ); ?>">
				<h2><?php echo esc_html( $group['title'] ); ?></h2>

				<ul>
					<?php
					foreach ( $group['addons'] as $addon ) {
						/* @var AC\Integration $addon */

						$view = new AC\View( [
							'logo'        => AC()->get_url() . $addon->get_logo(),
							'title'       => $addon->get_title(),
							'slug'        => $addon->get_slug(),
							'description' => $addon->get_description(),
							'actions'     => $this->render_actions( $addon ),
						] );

						echo $view->set_template( 'admin/edit-addon' );
					}
					?>
				</ul>
			</div>
		<?php endforeach;

		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	public function render_grouped_addons() {
		ob_start();
		foreach ( $this->get_grouped_addons() as $group_slug => $group ) :
			?>

			<div class="ac-addons group-<?php echo esc_attr( $group_slug ); ?>">
				<h2><?php echo esc_html( $group['title'] ); ?></h2>

				<ul>
					<?php
					foreach ( $group['addons'] as $addon ) {
						/* @var AC\Integration $addon */

						$view = new AC\View( [
							'logo'        => AC()->get_url() . $addon->get_logo(),
							'title'       => $addon->get_title(),
							'slug'        => $addon->get_slug(),
							'description' => $addon->get_description(),
							'actions'     => $this->render_actions( $addon ),
						] );

						echo $view->set_template( 'admin/edit-addon' );
					}
					?>
				</ul>
			</div>
		<?php endforeach;

		return ob_get_clean();
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

			<a href="#" class="button" data-install>
				<?php esc_html_e( 'Download & Install', 'codepress-admin-columns' ); ?>
			</a>
		<?php else : ?>
			<a target="_blank" href="<?php echo esc_url( $addon->get_link() ); ?>" class="button"><?php esc_html_e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
		<?php endif;

		return ob_get_clean();
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
		return add_query_arg( [
			'action'      => $action,
			'plugin'      => $basename,
			'ac-redirect' => true,
		], wp_nonce_url( admin_url( 'plugins.php' ), $action . '-plugin_' . $basename ) );
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
	 * Group a list of add-ons
	 * @return array A list of addons per group: [group_name] => (array) [group_addons], where [group_addons] is an array ([addon_name] => (array) [addon_details])
	 * @since 3.0
	 */
	private function get_grouped_addons() {
		$active = [];
		$inactive = [];

		foreach ( new AC\Integrations() as $integration ) {
			if ( $this->get_plugin_info( $integration->get_basename() )->is_active() ) {
				$active[] = $integration;
			} else {
				$inactive[] = $integration;
			}
		}

		/* @var AC\Integration[] $sorted */
		$sorted = array_merge( $active, $inactive );

		$grouped = [];
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
	 * Addons are grouped into addon groups by providing the group an addon belongs to.
	 * @return array Available addon groups ([group_name] => [label])
	 * @since 2.2
	 */
	public function get_addon_groups() {
		$addon_groups = [
			'installed'   => __( 'Installed', 'codepress-admin-columns' ),
			'recommended' => __( 'Recommended', 'codepress-admin-columns' ),
			'default'     => __( 'Available', 'codepress-admin-columns' ),
		];

		/**
		 * Filter the addon groups
		 *
		 * @param array $addon_groups Available addon groups ([group_name] => [label])
		 *
		 * @since 2.2
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

}