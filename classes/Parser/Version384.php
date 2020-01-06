<?php

namespace AC\Parser;

use AC\ListScreenCollection;
use AC\ListScreenTypes;
use DateTime;

class Version384 implements Decode {

	const VERSION = '3.8.4';

	/**
	 * @param array $data
	 *
	 * @return ListScreenCollection
	 */
	public function decode( array $data ) {
		$list_screens = new ListScreenCollection();

		foreach ( $data as $key => $list_screens_data ) {

			foreach ( $list_screens_data as $data ) {

				$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $key );

				if ( null === $list_screen ) {
					continue;
				}

				$id = trim( $data['layout']['id'] );

				if ( empty( $id ) ) {
					$id = sanitize_key( substr( md5( serialize( $data ) . $key ), 0, 16 ) );
				}

				$title = $data['layout']['name'];

				if ( ! $title ) {
					$title = ucfirst( $list_screen->get_label() );
				}

				$list_screen
					->set_layout_id( $id )
					->set_settings( $data['columns'] )
					->set_title( $title )
					->set_updated( new DateTime() );

				$settings = [];
				if ( isset( $data['layout']['users'] ) ) {
					$settings['users'] = array_map( 'intval', $data['layout']['users'] );
				}
				if ( isset( $data['layout']['roles'] ) ) {
					$settings['roles'] = array_map( 'strval', $data['layout']['roles'] );
				}

				$list_screen->set_preferences( $settings );

				$list_screens->push( $list_screen );
			}
		}

		return $list_screens;
	}

}