<?php
namespace AC\Admin;

// todo: DRY
class AbstractNetworkPageFactory extends AbstractPageFactory {

	private static $factories = array();

	public static function register( PageFactory $factory ) {
		self::$factories[] = $factory;
	}

	/**
	 * @param $key
	 *
	 * @return PageFactory|false
	 */
	public static function create( $slug ) {

		foreach( self::$factories as $factory ) {
			$page = $factory->create( $slug );

			if ( $page ) {
				return $page;
			}
		}

		return false;
	}

}