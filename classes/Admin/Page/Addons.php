<?php

namespace AC\Admin\Page;

use AC;
use AC\Admin;
use AC\Admin\RenderableHead;
use AC\Asset\Assets;
use AC\Asset\Enqueueables;
use AC\Asset\Location;
use AC\Asset\Style;
use AC\Container;
use AC\Integration\Filter;
use AC\IntegrationRepository;
use AC\Renderable;

class Addons implements Enqueueables, Renderable, RenderableHead {

	const NAME = 'addons';

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var IntegrationRepository
	 */
	protected $integrations;

	/**
	 * @var Renderable
	 */
	protected $head;

	public function __construct( Location\Absolute $location, IntegrationRepository $integrations, Renderable $head ) {
		$this->location = $location;
		$this->integrations = $integrations;
		$this->head = $head;
	}

	public function render_head() {
		return $this->head;
	}

	public function get_assets() {
		return new Assets( [
			new Style( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/css/admin-page-addons.css' ) ),
			new Admin\Asset\Addons( 'ac-admin-page-addons', $this->location->with_suffix( 'assets/js/admin-page-addons.js' ) ),
		] );
	}

	public function render() {
		ob_start();

		echo '<h1 class="screen-reader-text">' . __( 'Add-ons', 'codepress-admin-columns' ) . '</h1>';
		echo '<div class="ac-addons-groups">';

		foreach ( $this->get_grouped_addons() as $group ) :
			?>

			<div class="ac-addons group-<?= esc_attr( $group['class'] ); ?>">
				<h2 class="ac-lined-header"><?php echo $group['title']; ?></h2>

				<ul>
					<?php
					foreach ( $group['integrations'] as $addon ) {
						$actions = $this->render_actions( $addon );
						/* @var AC\Integration $addon */

						$view = new AC\View( [
							'logo'        => sprintf( '%s/%s', Container::get_url(), $addon->get_logo() ),
							'title'       => $addon->get_title(),
							'slug'        => $addon->get_slug(),
							'description' => $addon->get_description(),
							'link'        => $addon->get_link(),
							'actions'     => $actions ? $actions->render() : null,
						] );

						echo $view->set_template( 'admin/edit-addon' );
					}
					?>
				</ul>
			</div>
		<?php endforeach;

		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * @param AC\Integration $addon
	 *
	 * @return Renderable
	 */
	protected function render_actions( AC\Integration $addon ): ?Renderable {
		return new Admin\Section\AddonStatus( $addon );
	}

	/**
	 * @return array
	 */
	protected function get_grouped_addons() {
		$recommended = $this->integrations->find_all( [
			IntegrationRepository::ARG_FILTER => [
				new Filter\IsPluginActive(),
			],
		] );

		$available = $this->integrations->find_all( [
			IntegrationRepository::ARG_FILTER => [
				new Filter\IsPluginNotActive(),
			],
		] );

		$groups = [];

		if ( $recommended->exists() ) {
			$groups[] = [
				'title'        => __( 'Recommended', 'codepress-admin-columns' ),
				'class'        => 'recommended',
				'integrations' => $recommended,
			];
		}

		if ( $available->exists() ) {
			$groups[] = [
				'title'        => __( 'Available', 'codepress-admin-columns' ),
				'class'        => 'available',
				'integrations' => $available,
			];
		}

		return $groups;
	}

}