<?php

namespace AC;

use AC\Admin\Helpable;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\PageRequestHandler;
use AC\Admin\ScreenOptions;
use AC\Asset\Enqueueables;

class Admin implements Registrable, Renderable {

	const NAME = 'codepress-admin-columns';

	/**
	 * @var Enqueueables
	 */
	private $scripts;

	/**
	 * @var PageRequestHandler
	 */
	private $request_handler;

	/**
	 * @var MenuFactoryInterface
	 */
	private $menu_factory;

	public function __construct( Enqueueables $scripts, PageRequestHandler $request_handler, MenuFactoryInterface $menu_factory ) {
		$this->scripts = $scripts;
		$this->request_handler = $request_handler;
		$this->menu_factory = $menu_factory;
	}

	public function render() {
		$page = $this->request_handler->handle( new Request() );

		if ( ! $page ) {
			return;
		}

		$menu = $this->menu_factory->create();

		do_action( 'ac/admin/menu', $menu );

		?>
		<div id="cpac" class="wrap">
			<?php
			$view = new View( [
				'menu_items' => $menu->get_items(),
				'current'    => $page->get_slug(),
			] );

			echo $view->set_template( 'admin/menu' )->render();
			?>

			<?= $page->render() ?>

		</div>
		<?php
	}

	public function register() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		$page = $this->request_handler->handle( new Request() );

		if ( ! $page ) {
			return;
		}

		if ( $page instanceof Helpable ) {
			$this->help_tabs( $page, $screen );
		}
		if ( $page instanceof Enqueueables ) {
			$this->scripts( $page );
		}

		foreach ( $this->scripts->get_assets() as $asset ) {
			$asset->enqueue();
		}

		do_action( 'ac/admin_scripts' );
		do_action( 'ac/admin_scripts/' . $page->get_slug() );

		add_filter( 'screen_settings', [ $this, 'screen_options' ] );
	}

	protected function help_tabs( Helpable $page, \WP_Screen $screen ) {
		foreach ( $page->get_help_tabs() as $help ) {
			$screen->add_help_tab( [
				'id'      => $help->get_id(),
				'title'   => $help->get_title(),
				'content' => $help->get_content(),
			] );
		}
	}

	public function screen_options( $settings ) {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof ScreenOptions ) {
			$settings .= sprintf( '<legend>%s</legend>', __( 'Display', 'codepress-admin-columns' ) );

			foreach ( $page->get_screen_options() as $screen_option ) {
				$settings .= $screen_option->render();
			}
		}

		return $settings;
	}

	protected function scripts( Enqueueables $page ) {
		foreach ( $page->get_assets() as $asset ) {
			$asset->enqueue();
		}
	}

}