<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin;
use AC\Admin\Page;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\PluginInformation;

class Addons extends Page implements Enqueueables {

	const NAME = 'addons';

	/**
	 * @var Location\Absolute
	 */
	private $location;

	/**
	 * @var AC\Integrations
	 */
	private $integrations;

	public function __construct( Location\Absolute $location, AC\Integrations $integrations ) {
		parent::__construct( self::NAME, __( 'Add-ons', 'codepress-admin-columns' ) );

		$this->location = $location;
		$this->integrations = $integrations;
	}

	public function get_assets() {
		return new Assets( [
			new Style( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/css/admin-page-addons.css' ) ),
			new Admin\Asset\Addons( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/js/admin-page-addons.js' ) ),
		] );
	}

	public function render() {
		ob_start();

		foreach ( $this->get_grouped_addons() as $group ) :
			?>

			<div class="ac-addons group-<?= esc_attr( $group['class'] ); ?>">
				<h2><?php echo esc_html( $group['title'] ); ?></h2>

				<ul>
					<?php
					foreach ( $group['integrations'] as $addon ) {
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

		$plugin = new PluginInformation( $addon->get_basename() );

		// Installed..
		if ( $plugin->is_installed() ) :

			// Active
			if ( $plugin->is_active() ) : ?>
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
	 * @param string $basename
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
	 * @return array
	 */
	private function get_grouped_addons() {

		$active = [];
		$recommended = [];
		$available = [];

		foreach ( $this->integrations->all() as $integration ) {
			$plugin = new PluginInformation( $integration->get_basename() );

			// active
			if ( $plugin->is_active() ) {
				$active[] = $integration;
				continue;
			}

			// recommended
			if ( $integration->is_plugin_active() ) {
				$recommended[] = $integration;
				continue;
			}

			$available[] = $integration;
		}

		$groups = [];

		if ( $recommended ) {
			$groups[] = [
				'title'        => __( 'Recommended', 'codepress-admin-columns' ),
				'class'        => 'recommended',
				'integrations' => $recommended,
			];
		}

		if ( $active ) {
			$groups[] = [
				'title'        => __( 'Active', 'codepress-admin-columns' ),
				'class'        => 'active',
				'integrations' => $active,
			];
		}

		if ( $available ) {
			$groups[] = [
				'title'        => __( 'Available', 'codepress-admin-columns' ),
				'class'        => 'available',
				'integrations' => $available,
			];
		}

		return $groups;
	}

}