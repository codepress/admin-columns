<?php
namespace AC;

use AC\Admin\AbstractPageFactory;
use AC\Admin\Helpable;
use AC\Admin\MenuItem;
use AC\Admin\Page;

abstract class Admin implements Registrable {

	const PLUGIN_PAGE = 'codepress-admin-columns';

	/** @var string */
	private $hook_suffix;

	/** @var string */
	private $parent_page;

	/** @var Page */
	private $page;

	/** @var AbstractPageFactory */
	protected $page_factory;

	/** @var array */
	private $menu_items;

	public function __construct( $parent_page, AbstractPageFactory $page_factory ) {
		$this->parent_page = $parent_page;
		$this->page_factory = $page_factory;
	}

	/**
	 * @param string $menu_item
	 *
	 * @return $this
	 */
	public function add_menu_item( $menu_item ) {
		$this->menu_items[] = $menu_item;

		return $this;
	}

	/**
	 * Register hooks
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
	 * @return string
	 */
	private function get_tab() {
		return filter_input( INPUT_GET, 'tab' );
	}

	/**
	 * @return void
	 */
	public function init() {
		$tab = $this->get_tab();

		if ( ! $tab ) {
			$tab = 'columns';
		}

		$page = $this->page_factory->create( $tab );

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

	/**
	 * @param string $tab
	 *
	 * @return string
	 */
	public function get_url( $tab ) {
		$args = array(
			'tab'  => $tab,
			'page' => self::PLUGIN_PAGE,
		);

		return add_query_arg( $args, $this->get_parent_page() );
	}

	/**
	 * @return MenuItem[]
	 */
	private function get_menu_items() {
		$items = array();

		foreach ( $this->menu_items as $slug ) {
			$page = $this->page_factory->create( $slug );

			if ( $page ) {
				$items[] = new MenuItem( $page->get_slug(), $page->get_label(), $this->get_url( $page->get_slug() ) );
			}
		}

		return $items;
	}

	/**
	 * @return void
	 */
	public function render() {
		?>
		<div id="cpac" class="wrap">
			<?php

			$menu = new View( array(
				'items'   => $this->get_menu_items(),
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