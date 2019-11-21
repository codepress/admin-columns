<?php
namespace AC\Parser;

use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;

class Version332 implements Decode {

	const VERSION = '3.3.2';

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data ) {
		$list_screens = new ListScreenCollection();

		foreach ( $data as $key => $columns ) {
			$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $key );

			if ( null === $list_screen ) {
				continue;
			}

			$id = sanitize_key( substr( md5( serialize( $columns ) . $key ), 0, 16 ) );

			$list_screen
				->set_layout_id( $id )
				->set_settings( $columns )
				->set_title( __( 'Original', 'codepress-admin-columns' ) )
				->set_updated( new DateTime() );

			$list_screens->push( $list_screen );
		}

		return $list_screens;
	}

}