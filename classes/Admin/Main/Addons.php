<?php

namespace AC\Admin\Main;

use AC;
use AC\Admin;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\PluginInformation;
use AC\Renderable;

class Addons implements Enqueueables, Renderable {

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
		$view = new Admin\AddonStatus( $plugin, $addon, is_multisite(), is_network_admin() );
		$view->render();

		return ob_get_clean();
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

			$is_active = $plugin->is_network_active()
			             || ( ! is_multisite() && $plugin->is_active() )
			             || ( is_multisite() && ! is_network_admin() && $plugin->is_active() );

			if ( $is_active ) {
				$active[] = $integration;
				continue;
			}

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