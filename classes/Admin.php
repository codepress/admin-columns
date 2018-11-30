<?php
namespace AC;

use AC\Admin\Helpable;
use AC\Admin\Page;
use AC\Admin\PageFactory;

abstract class Admin {

	const PLUGIN_PAGE = 'codepress-admin-columns';

	/** @var string */
	private $hook_suffix;

	/** @var string */
	private $parent_page;

	/** @var Page */
	private $page;

	/** @var PageFactory */
	protected $page_factory;

	public function __construct( $parent_page, PageFactory $page_factory ) {
		$this->parent_page = $parent_page;
		$this->page_factory = $page_factory;
	}

	/**
	 * @return array
	 */
	abstract public function menu_items();

	/**
	 * @return void
	 */
	abstract public function register();

	/**
	 * @return string
	 */
	public function get_parent_page() {
		return $this->parent_page;
	}

	/**
	 * @return void
	 */
	public function settings_menu() {
		$this->hook_suffix = add_submenu_page(
			$this->parent_page,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::PLUGIN_PAGE,
			function () {
			}
		);

		add_action( "load-" . $this->hook_suffix, array( $this, 'init' ) );
		add_action( "admin_print_scripts-" . $this->hook_suffix, array( $this, 'admin_scripts' ) );
	}

	/**
	 * @return void
	 */
	public function init() {
		$page = $this->page_factory->create( filter_input( INPUT_GET, 'tab' ) );

		if ( $page instanceof Registrable ) {
			$page->register();
		}

		if ( $page instanceof Helpable ) {
			foreach ( $page->get_help_tabs() as $help ) {
				get_current_screen()->add_help_tab( array(
					'id'      => $help->get_id(),
					'content' => $help->get_content(),
					'title'   => $help->get_title(),
				) );
			}
		}

		$this->page = $page;

		add_action( $this->hook_suffix, array( $this, 'render' ) );
	}

	private function items() {
		// todo
		$items = array();
		foreach ( $this->menu_items() as $slug ) {
			$page = $this->page_factory->create( $slug );

			$items[ $slug ] = $page->get_label();
		}

		return $items;
	}

	public function render() {
		?>
		<div id="cpac" class="wrap">
			<?php

			$menu = new View( array(
				'items'   => $this->items(),
				'current' => $this->page->get_slug(),
			) );

			echo $menu->set_template( 'admin/edit-tabmenu' );

			$this->page->render();
			?>
		</div>
		<?php
	}

	/**
	 * Scripts
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'ac-admin-general', AC()->get_url() . "assets/js/admin-general.js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_url() . "assets/css/admin-general.css", array(), AC()->get_version() );

		do_action( 'ac/admin_scripts' );
	}

}