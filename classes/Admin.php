<?php

namespace AC;

use AC\Admin\AdminMenu;
use AC\Admin\AdminView;
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

	public function __construct( $parent_slug, $menu_hook, Enqueueables $scripts, PageRequestHandler $request_handler ) {
		$this->parent_slug = $parent_slug;
		$this->menu_hook = $menu_hook;
		$this->scripts = $scripts;
		$this->request_handler = $request_handler;
	}

	public function register() {
		add_action( $this->menu_hook, [ $this, 'init' ] );
	}

	public function init() {
		// TODO
		$admin_view = new AdminView(
			$this->request_handler,
			new AdminMenu( $this->parent_slug )
		);

		$hook = add_submenu_page(
			$this->parent_slug,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::NAME,
			[ $admin_view, 'render' ]
		);

		add_action( "load-" . $hook, [ $this, 'scripts' ] );
		add_action( "load-" . $hook, [ $this, 'help_tabs' ] );
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

		foreach ( $this->scripts as $asset ) {
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