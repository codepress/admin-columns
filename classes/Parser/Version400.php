<?php
namespace AC\Parser;

use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;
use RuntimeException;

class Version400 implements Decode, Encode {

	const VERSION = '4.0.0';

	const VERSION_KEY = 'version';
	const LISTSCREENS_KEY = 'list_screens';

	const TITLE_KEY = 'title';
	const TYPE_KEY = 'type';
	const ID_KEY = 'id';
	const UPDATED_KEY = 'date_modified';
	const COLUMNS_KEY = 'columns';
	const SETTINGS_KEY = 'settings';

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data ) {
		$list_screens = new ListScreenCollection();

		if ( ! array_key_exists( self::LISTSCREENS_KEY, $data ) ) {
			throw new RuntimeException( 'Invalid list screen data.' );
		}

		foreach ( $data[ self::LISTSCREENS_KEY ] as $_data ) {

			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $_data[ self::TYPE_KEY ] );

			if ( null === $list_screen ) {
				continue;
			}

			$title = $_data[ self::TITLE_KEY ];

			if ( ! $title ) {
				$title = ucfirst( $list_screen->get_label() );
			}

			$list_screen
				->set_layout_id( $_data[ self::ID_KEY ] )
				->set_settings( $_data[ self::COLUMNS_KEY ] )
				->set_preferences( $_data[ self::SETTINGS_KEY ] )
				->set_title( $title )
				->set_updated( DateTime::createFromFormat( 'U', (int) $_data[ self::UPDATED_KEY ] ) );

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
			self::VERSION_KEY => self::VERSION,
		];

		foreach ( $listScreens as $listScreen ) {
			$data[ self::LISTSCREENS_KEY ][] = $this->toArray( $listScreen );
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
			self::TITLE_KEY    => $listScreen->get_title(),
			self::TYPE_KEY     => $listScreen->get_key(),
			self::ID_KEY       => $listScreen->get_layout_id(),
			self::UPDATED_KEY  => $listScreen->get_updated()->getTimestamp(),
			self::COLUMNS_KEY  => $listScreen->get_settings(),
			self::SETTINGS_KEY => $listScreen->get_preferences(),
		];
	}

}