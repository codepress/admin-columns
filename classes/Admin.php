<?php

namespace AC;

use AC\Admin\Helpable;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\PageRequestHandler;
use AC\Admin\ScreenOptions;
use AC\Asset\Enqueueables;

class Admin {

	const NAME = 'codepress-admin-columns';

	const QUERY_ARG_PAGE = MenuFactoryInterface::QUERY_ARG_PAGE;
	const QUERY_ARG_TAB = MenuFactoryInterface::QUERY_ARG_TAB;

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

	public function get_capability() {
		return Capabilities::MANAGE;
	}

	public function get_slug() {
		return self::NAME;
	}

	public function render() {
		$page = $this->request_handler->handle( new Request() );

		?>
		<div id="cpac" class="wrap">
			<?php
			$view = new View( [
				'menu_items' => $this->menu_factory->create()->get_copy(),
				'current'    => $page->get_slug(),
			] );

			echo $view->set_template( 'admin/menu' )->render();
			?>

			<?= $page->render() ?>

		</div>
		<?php
	}

	public function load() {
		$page = $this->request_handler->handle( new Request() );

		$this->help_tabs();
		$this->scripts();

		do_action( 'ac/admin_scripts' );
		do_action( 'ac/admin_scripts/' . $page->get_slug() );

		add_filter( 'screen_settings', [ $this, 'screen_options' ] );
	}

	protected function help_tabs() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Helpable ) {
			foreach ( $page->get_help_tabs() as $help ) {
				$screen->add_help_tab( [
					'id'      => $help->get_id(),
					'title'   => $help->get_title(),
					'content' => $help->get_content(),
				] );
			}
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

	protected function scripts() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Enqueueables ) {
			foreach ( $page->get_assets() as $asset ) {
				$asset->enqueue();
			}
		}

		foreach ( $this->scripts->get_assets() as $asset ) {
			$asset->enqueue();
		}
	}

}