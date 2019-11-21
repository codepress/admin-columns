<?php
namespace AC\Parser;

use AC\ListScreen;
use AC\ListScreenCollection;

abstract class Encode {

	/**
	 * @param ListScreenCollection $listScreens
	 *
	 * @return string
	 */
	abstract function encode( ListScreenCollection $listScreens );

	/**
	 * @param ListScreen $listScreen
	 *
	 * @return array
	 */
	protected function toArray( ListScreen $listScreen ) {
		return [
			Dto\ListScreen::TITLE_KEY    => $listScreen->get_title(),
			Dto\ListScreen::TYPE_KEY     => $listScreen->get_key(),
			Dto\ListScreen::ID_KEY       => $listScreen->get_layout_id(),
			Dto\ListScreen::UPDATED_KEY  => $listScreen->get_updated()->getTimestamp(),
			Dto\ListScreen::COLUMNS_KEY  => $listScreen->get_settings(),
			Dto\ListScreen::SETTINGS_KEY => $listScreen->get_preferences(),
		];
	}

}