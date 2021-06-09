<?php

namespace AC\Admin;

use AC\Admin;
use AC\Renderable;
use AC\View;

class Menu implements Renderable {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $current;

	/**
	 * @var Admin\Menu\Item[]
	 */
	private $items;

	public function __construct( $url, $current ) {
		$this->url = $url;
		$this->current = $current;
	}

	public function add_item( $slug, $label ) {
		$this->items[] = new Menu\Item( $slug, $label, $this->create_menu_link( $slug ) );

		return $this;
	}

	public function remove_item( $slug ) {
		unset( $this->items[ $slug ] );

		return $this;
	}

	public function get_items() {
		return $this->items;
	}

	/**
	 * @return string
	 */
	public function get_current() {
		return $this->current;
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				RequestHandler::PARAM_PAGE => Admin\Admin::NAME,
				RequestHandler::PARAM_TAB  => $slug,
			],
			$this->url
		);
	}

	public function render() {
		$view = new View( [
			'menu_items' => $this->get_items(),
			'current'    => $this->current,
		] );

		return $view->set_template( 'admin/menu' )->render();
	}

}