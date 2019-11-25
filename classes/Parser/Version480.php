<?php
namespace AC\Parser;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenTypes;
use AC\Parser\Dto;
use DateTime;

class Version480 implements Decode, Encode {

	const VERSION = 'NEWVERSION';

	const VERSION_KEY = 'version';
	const LISTSCREENS_KEY = 'list_screens';

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data ) {
		$list_screens = new ListScreenCollection();

		foreach ( $data[ self::LISTSCREENS_KEY ] as $_data ) {

			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $_data[ Dto\ListScreen::TYPE_KEY ] );

			if ( null === $list_screen ) {
				continue;
			}

			$title = $_data[ Dto\ListScreen::TITLE_KEY ];

			if ( ! $title ) {
				$title = ucfirst( $list_screen->get_label() );
			}

			$list_screen
				->set_layout_id( $_data[ Dto\ListScreen::ID_KEY ] )
				->set_settings( $_data[ Dto\ListScreen::COLUMNS_KEY ] )
				->set_preferences( $_data[ Dto\ListScreen::SETTINGS_KEY ] )
				->set_title( $title )
				->set_updated( DateTime::createFromFormat( 'U', (int) $_data[ Dto\ListScreen::UPDATED_KEY ] ) );

			$list_screens->push( $list_screen );
		}

		return $list_screens;
	}

	/**
	 * @param ListScreenCollection $listScreens
	 *
	 * @return array
	 */
	public function encode( ListScreenCollection $listScreens ) {
		$data = [
			self::VERSION_KEY => self::VERSION
		];

		foreach ( $listScreens as $listScreen ) {
			$data[ self::LISTSCREENS_KEY ] = $this->toArray( $listScreen );
		}

		return $data;
	}

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