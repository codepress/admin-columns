<?php

namespace AC\Admin;

use AC\Admin;

class Menu {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var Admin\Menu\Item[]
	 */
	private $items;

	public function __construct( $url ) {
		$this->url = $url;
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
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				PageRequestHandler::PARAM_PAGE => Admin::NAME,
				PageRequestHandler::PARAM_TAB  => $slug,
			],
			$this->url
		);
	}

}