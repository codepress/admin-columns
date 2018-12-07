<?php
namespace AC\Admin;

class AbstractPageFactory {

	private $factories = array();

	public function register( PageFactory $factory ) {
		$this->factories[] = $factory;
	}

	/**
	 * @param $key
	 *
	 * @return Page|false
	 */
	public function create( $slug ) {

		foreach( $this->factories as $factory ) {
			$page = $factory->create( $slug );

			if ( $page ) {
				return $page;
			}
		}

		return false;
	}

}