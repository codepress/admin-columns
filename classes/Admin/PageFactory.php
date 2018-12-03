<?php
namespace AC\Admin;

use AC\Admin\Page\Settings;
use AC\Settings\Admin\General\ShowEditButton;

class PageFactory {

	/** @var Settings */
	private $settings = null;

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function create( $slug = false ) {

		switch ( $slug ) {

			case 'addons' :
				return new Page\Addons();

			case 'help' :
				return new Page\Help();

			case 'settings' :
				if ( null === $this->settings ) {

					$general = new Section\General();
					$general->register_setting( new ShowEditButton );

					$settings = new Page\Settings();
					$settings->register_section( $general )
					         ->register_section( new Section\Restore );

					$this->settings = $settings;
				}

				return $this->settings;

			case 'columns' :
				return new Page\Columns();

			default :
				return false;
		}
	}

}