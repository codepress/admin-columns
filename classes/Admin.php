<?php

namespace AC;

use AC\Admin\AdminMenuFactory;
use AC\Admin\Helpable;
use AC\Admin\PageRequestHandler;
use AC\Admin\ScreenOptions;
use AC\Asset\Enqueueables;

class Admin implements Registrable {

	const NAME = 'codepress-admin-columns';

	const QUERY_ARG_PAGE = 'page';
	const QUERY_ARG_TAB = 'tab';

	/**
	 * @var string
	 */

	private $parent_slug;

	/**
	 * @var string
	 */
	private $menu_hook;

	/**
	 * @var Enqueueables
	 */
	private $scripts;

	/**
	 * @var PageRequestHandler
	 */
	private $request_handler;

	/**
	 * @var AdminMenuFactory
	 */
	private $menu_factory;

	public function __construct( $parent_slug, $menu_hook, Enqueueables $scripts, PageRequestHandler $request_handler, AdminMenuFactory $menu_factory ) {
		$this->parent_slug = $parent_slug;
		$this->menu_hook = $menu_hook;
		$this->scripts = $scripts;
		$this->request_handler = $request_handler;
		$this->menu_factory = $menu_factory;
	}

	public function register() {
		add_action( $this->menu_hook, [ $this, 'init' ] );
	}

	public function init() {

		$hook = add_submenu_page(
			$this->parent_slug,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::NAME,
			[ $this, 'render' ]
		);

		add_action( "load-" . $hook, [ $this, 'scripts' ] );
		add_action( "load-" . $hook, [ $this, 'help_tabs' ] );
	}

	public function render() {
		$page = $this->request_handler->handle( new Request() );

		?>
		<div id="cpac" class="wrap">
			<?php
			$view = new View( [
				'menu_items' => $this->menu_factory->create( $this->parent_slug )->get_copy(),
				'current'    => $page->get_slug(),
			] );

			echo $view->set_template( 'admin/menu' )->render();
			?>

			<?= $page->render() ?>

		</div>
		<?php
	}

	public function help_tabs() {
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

		add_filter( 'screen_settings', [ $this, 'screen_options' ] );
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

	public function scripts() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Enqueueables ) {
			foreach ( $page->get_assets() as $asset ) {
				$asset->enqueue();
			}
		}

		foreach ( $this->scripts->get_assets() as $asset ) {
			$asset->enqueue();
		}

		do_action( 'ac/admin_scripts' );
		do_action( 'ac/admin_scripts/' . $page->get_slug() );

		/**
		 * @deprecated 4.1
		 */
		do_action_deprecated( 'ac/settings/scripts', null, '4.1', 'ac/admin_scripts' );
	}

}