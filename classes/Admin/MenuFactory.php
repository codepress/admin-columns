<?php

namespace AC\Admin;

use AC\Admin\Menu\Item;
use AC\Asset\Location;
use AC\Deprecated\Hooks;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class MenuFactory implements MenuFactoryInterface {

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	public function __construct( $url, Location\Absolute $location ) {
		$this->url = (string) $url;
		$this->location = $location;
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				RequestHandlerInterface::PARAM_PAGE => Admin::NAME,
				RequestHandlerInterface::PARAM_TAB  => $slug,
			],
			$this->url
		);
	}

	public function create( $current ) {
		$menu = new Menu();

		$items = [
			Page\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Page\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Page\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$items[ Page\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' );
		}

		foreach ( $items as $slug => $label ) {
			$menu->add_item( new Item( $slug, $this->create_menu_link( $slug ), $label, sprintf( '-%s %s', $slug, $current === $slug ? '-active' : '' ) ) );
		}

		$url = ( new UtmTags( new Site( Site::PAGE_ABOUT_PRO ), 'upgrade' ) )->get_url();
		$image = sprintf( '<img alt="%s" src="%s">', 'Admin Columns Pro', $this->location->with_suffix( '/assets/images/external.svg' )->get_url() );

		$menu->add_item( new Item( 'pro', $url, sprintf( '%s %s', 'Admin Columns Pro', $image ), '-pro', '_blank' ) );

		do_action( 'ac/admin/page/menu', $menu );

		return $menu;
	}

}