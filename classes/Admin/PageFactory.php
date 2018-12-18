<?php
namespace AC\Admin;

use AC\Admin\Page\Addons;
use AC\Admin\Page\Columns;
use AC\Admin\Page\Help;
use AC\Admin\Page\Settings;

class PageFactory implements PageFactoryInterface {

	/** @var Settings */
	private $settings = null;

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function create( $slug = false ) {

		switch ( $slug ) {

			case Addons::NAME :
				return new Page\Addons();

			case Help::NAME :
				return new Page\Help();

			case Settings::NAME :
				if ( null === $this->settings ) {

					$settings = new Page\Settings();
					$settings->register_section( GeneralSectionFactory::create() )
					         ->register_section( new Section\Restore );

					$this->settings = $settings;
				}

				return $this->settings;

			case Columns::NAME :
				return new Page\Columns();

			default :
				return false;
		}
	}

	/**
	 * @return array
	 */
	public function get_slugs() {
		return array(
			Columns::NAME,
			Settings::NAME,
			Addons::NAME,
			Help::NAME,
		);
	}

}