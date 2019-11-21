<?php
namespace AC\Parser;

use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;
use RuntimeException;

class Version480 implements Decode {

	const VERSION = 'NEWVERSION';

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data ) {
		$list_screens = new ListScreenCollection();

		foreach ( $data['list_screens'] as $_data ) {

			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $_data[ Dto\ListScreen::TYPE_KEY ] );

			if ( null === $list_screen ) {
				throw new RuntimeException( sprintf( 'List screen %s is not avaible.', $_data[ Dto\ListScreen::TYPE_KEY ] ) );
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

}