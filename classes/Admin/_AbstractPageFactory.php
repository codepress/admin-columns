<?php
namespace AC\Admin;

// todo: remove
class _AbstractPageFactory {

	private static $factories = array();

	public static function register( PageFactory $factory ) {
		self::$factories[] = $factory;
	}

	/**
	 * @param $key
	 *
	 * @return Page|false
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